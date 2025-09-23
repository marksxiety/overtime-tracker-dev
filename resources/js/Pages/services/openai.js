import OpenAI from "openai";

const openai = new OpenAI({
    apiKey: import.meta.env.VITE_OPENAI_API_KEY,
    dangerouslyAllowBrowser: true,
});

export async function analyzeWithAI(jsonData, onChunk) {
    try {
        const content =
            typeof jsonData === "string"
                ? jsonData
                : JSON.stringify(jsonData, null, 2);

        const result = await openai.chat.completions.create({
            model: "gpt-4o-mini",
            stream: true,
            messages: [
                {
                    role: "system",
                    content: `You are a **professional overtime request analyzer**.  
                    Analyze overtime request JSON and produce a **consolidated Markdown report** for management:  
                    - List all employee name(s).  
                    - Quote the overtime reasons.  
                    - Identify the **main/common reason(s)** across the requests (focus on reasons, not employees).  
                    - Indicate whether overtime typically occurs during the **day or night**.  
                    - Break down which parts of the reasons justify the overtime.  
                    - Provide a professional justification section summarizing the overall need.`,
                },
                {
                    role: "user",
                    content: `Analyze this JSON overtime request:\n\n${content}`,
                },
            ],
        });

        for await (const chunk of result) {
            const delta = chunk.choices?.[0]?.delta?.content;
            if (delta) {
                onChunk(delta);
            }
        }

        return { success: true };
    } catch (error) {
        console.error("AI Analysis Error:", error);
        return {
            success: false,
            data: error || "Unidentified Error Occurred",
        };
    }
}
