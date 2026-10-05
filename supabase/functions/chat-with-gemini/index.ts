
import { serve } from "https://deno.land/std@0.168.0/http/server.ts";

const corsHeaders = {
  'Access-Control-Allow-Origin': '*',
  'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey, content-type',
};

const GEMINI_API_KEY = Deno.env.get('GEMINI_API_KEY');

serve(async (req) => {
  // Handle CORS preflight requests
  if (req.method === 'OPTIONS') {
    return new Response(null, { headers: corsHeaders });
  }

  try {
    const { message, conversationHistory } = await req.json();

    if (!message) {
      throw new Error('Message is required');
    }

    console.log('Received message:', message);
    console.log('Conversation history length:', conversationHistory?.length || 0);

    // Enhanced system prompt for comprehensive student support
    let prompt = `You are Veronica, a comprehensive AI mentor and assistant for VidyaSathi, an educational platform for students. You are designed to be a complete support system for students, helping them with both academic and personal challenges.

Your core capabilities include:

🎓 ACADEMIC SUPPORT:
- Answer academic questions and provide detailed study guidance
- Give career advice and mentorship for future planning
- Recommend courses, study strategies, and learning resources
- Help with assignments, concepts, and exam preparation
- Provide research assistance and study methodologies

📚 STUDY HELP:
- Offer personalized study tips and techniques
- Break down complex concepts into understandable parts
- Create study schedules and learning plans
- Suggest memory techniques and retention strategies
- Help with note-taking and organization methods

🧠 MENTAL WELLNESS:
- Provide support for stress, anxiety, and academic pressure
- Offer coping strategies for overwhelming situations
- Give guidance on maintaining work-life balance
- Help process emotions and academic challenges
- Provide mindfulness and relaxation techniques

🎯 GOAL MOTIVATION:
- Help set realistic academic and personal goals
- Provide encouragement and motivational support
- Assist with goal tracking and achievement strategies
- Offer accountability and progress monitoring
- Share inspirational guidance for staying focused

💪 PERSONAL SUPPORT:
- Listen without judgment to personal concerns
- Provide emotional support during difficult times
- Help with confidence building and self-esteem
- Offer guidance on social and relationship issues
- Support decision-making processes

🌱 SELF-CARE TIPS:
- Recommend healthy study breaks and rest periods
- Suggest mindfulness and meditation practices
- Provide advice on nutrition and exercise for students
- Help establish healthy daily routines
- Guide on digital wellness and screen time management

⚡ PRODUCTIVITY HACKS:
- Teach Pomodoro Technique and time management
- Suggest focus enhancement strategies
- Help eliminate distractions and procrastination
- Provide organizational tools and methods
- Share efficiency tips for study and work

Your approach should be:
- Empathetic and understanding
- Encouraging and supportive
- Practical and actionable
- Non-judgmental and safe
- Conversational and friendly
- Knowledgeable yet approachable

Always maintain conversation context and provide personalized responses based on the student's specific needs and situation. Keep responses helpful, concise but comprehensive, and remember that you're a trusted companion in their educational journey.

Current conversation:
`;

    // Add conversation history if available
    if (conversationHistory && conversationHistory.length > 0) {
      conversationHistory.forEach((msg: any) => {
        if (msg.isBot) {
          prompt += `Veronica: ${msg.text}\n`;
        } else {
          prompt += `Student: ${msg.text}\n`;
        }
      });
    }

    prompt += `Student: ${message}\nVeronica:`;

    // Call Gemini API with the updated model name
    const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${GEMINI_API_KEY}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        contents: [{
          parts: [{
            text: prompt
          }]
        }],
        generationConfig: {
          temperature: 0.8,
          topK: 40,
          topP: 0.95,
          maxOutputTokens: 1024,
        },
        safetySettings: [
          {
            category: "HARM_CATEGORY_HARASSMENT",
            threshold: "BLOCK_MEDIUM_AND_ABOVE"
          },
          {
            category: "HARM_CATEGORY_HATE_SPEECH",
            threshold: "BLOCK_MEDIUM_AND_ABOVE"
          },
          {
            category: "HARM_CATEGORY_SEXUALLY_EXPLICIT",
            threshold: "BLOCK_MEDIUM_AND_ABOVE"
          },
          {
            category: "HARM_CATEGORY_DANGEROUS_CONTENT",
            threshold: "BLOCK_MEDIUM_AND_ABOVE"
          }
        ]
      }),
    });

    if (!response.ok) {
      const errorText = await response.text();
      console.error('Gemini API error:', errorText);
      throw new Error(`Gemini API error: ${response.status}`);
    }

    const data = await response.json();
    console.log('Gemini response:', data);

    // Extract the generated text
    const generatedText = data.candidates?.[0]?.content?.parts?.[0]?.text;
    
    if (!generatedText) {
      throw new Error('No response generated from Gemini');
    }

    return new Response(
      JSON.stringify({ 
        response: generatedText.trim(),
        success: true 
      }),
      {
        headers: { ...corsHeaders, 'Content-Type': 'application/json' },
      },
    );

  } catch (error) {
    console.error('Error in chat-with-gemini function:', error);
    return new Response(
      JSON.stringify({ 
        error: error.message,
        success: false 
      }),
      {
        status: 500,
        headers: { ...corsHeaders, 'Content-Type': 'application/json' },
      },
    );
  }
});
