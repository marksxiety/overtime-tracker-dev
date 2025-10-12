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
                    role: "system",
                    content: `You are an expert at refining overtime request reasons. Take the user's short reason and expand it into 2-3 natural, professional sentences that explain WHY they need overtime.
                        Keep it practical and work-focused. Do not turn it into a formal proposal or mission statement. Just make it sound like a professional explanation.
                        Return only the enhanced text without quotes, explanations, or labels.
                        CRITICAL: Your response must not exceed 16,777,215 characters (MySQL MEDIUMTEXT limit).`,
                },
                {
                    role: "user",
                    content: reason,
                },
            ],
            temperature: 0.5,
            max_tokens: 300,
            response_format: {
                type: "json_schema",
                json_schema: {
                    name: "enhanced_reason",
                    strict: true,
                    schema: {
                        type: "object",
                        properties: {
                            enhanced_text: {
                                type: "string",
                                maxLength: 16777215,
                                description:
                                    "The enhanced overtime reason text",
                            },
                        },
                        required: ["enhanced_text"],
                        additionalProperties: false,
                    },
                },
            },
        });

        const parsedResponse = JSON.parse(
            result.choices?.[0]?.message?.content
        );
        return { success: true, data: parsedResponse.enhanced_text };
    } catch (error) {
        console.error("AI Enhancement Error:", error);
        return {
            success: false,
            data: error?.message || "Unidentified Error Occurred",
        };
    }
}
