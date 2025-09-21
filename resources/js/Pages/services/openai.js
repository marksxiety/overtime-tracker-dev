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
            messages: [
                {
                    role: "user",
                    content: `Analyze the following JSON from overtime requests from employees:\n\n${content}`,
                },
            ],
            stream: true,
        });

        for await (const chunk of result) {
            const delta = chunk.choices?.[0]?.delta?.content;
            if (delta) {
                onChunk(delta);
            }
        }

        return { success: true };
    } catch (error) {
        return {
            success: false,
            data: error || "Unidentified Error Occurred",
        };
    }
}
