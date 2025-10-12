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
                    content: `You are a senior workforce analytics consultant specializing in overtime analysis for management decision-making.

                    **Your Primary Objectives:**
                    1. Identify and categorize the most common reasons employees require overtime
                    2. Analyze shift patterns (day vs. night) where overtime frequently occurs
                    3. Provide strategic insights to help management understand overtime necessity
                    4. Present findings in a professional, data-driven manner

                    **Analysis Framework:**
                    - **Common Overtime Drivers**: Group similar reasons and highlight patterns (e.g., project deadlines, staff shortages, operational demands, client requirements, system maintenance, etc.)
                    - **Shift Analysis**: Clearly distinguish between day shift and night shift overtime occurrences with explanations
                    - **Business Justification**: Emphasize how these overtime instances support business continuity, client satisfaction, or operational efficiency
                    - **Frequency Patterns**: Note which reasons appear most frequently to help management prioritize solutions

                    **Output Format:**
                    Provide a structured Markdown report with:
                    - Executive summary of key findings
                    - Common overtime reasons with business context
                    - Shift distribution analysis
                    - Management insights and implications
                    
                    Keep the tone authoritative yet accessible, focusing on actionable insights rather than individual case details. Your analysis should help management understand that overtime is often a necessary business investment rather than just an additional cost.`,
                },
                {
                    role: "user",
                    content: `Please analyze the following overtime request data and provide a comprehensive management summary focusing on common patterns and business justifications:\n\n${content}`,
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

export async function enhanceReasonWithAI(reason) {
    try {
        const result = await openai.chat.completions.create({
            model: "gpt-4o-mini",
            messages: [
                {
                    role: "user",
                    content: `
                            You are a reason enhancer expert for overtime requests.
                            The input text will be stored in a MySQL MEDIUMTEXT column, which supports up to 16,777,215 characters.
                            Ensure your enhanced response remains concise, natural, and well-structured for this context.

                            Please enhance the following reason for an overtime request:

                            ${reason}`.trim(),
                },
            ],
        });

        return { success: true, data: result.choices?.[0]?.message?.content };
    } catch (error) {
        console.error("AI Enhancement Error:", error);
        return {
            success: false,
            data: error?.message || "Unidentified Error Occurred",
        };
    }
}
