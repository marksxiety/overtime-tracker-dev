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
                    content: `You are a professional overtime request analyzer.  
                    Your task is to review the provided JSON overtime requests and produce a **concise Markdown summary** for management.  
                    Focus mainly on identifying the **general/common reasons** employees work overtime.  
                    You may highlight typical timing (day vs. night) if it is relevant, but avoid breaking down each request individually.  
                    Keep the response clear, professional, and easy to understand.`,
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
