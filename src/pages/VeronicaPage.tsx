
import { useState, useEffect, useRef } from 'react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { MessageCircle, Send, Loader2, ArrowLeft } from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import { supabase } from '@/integrations/supabase/client';

export default function VeronicaPage() {
  const navigate = useNavigate();
  const messagesEndRef = useRef<HTMLDivElement>(null);
  const [messages, setMessages] = useState([
    {
      id: 1,
      text: "Hi! I'm Veronica, your AI mentor. How can I help you today?",
      isBot: true
    }
  ]);
  const [inputMessage, setInputMessage] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  };

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  const handleSendMessage = async () => {
    if (!inputMessage.trim() || isLoading) return;

    const newMessage = {
      id: messages.length + 1,
      text: inputMessage,
      isBot: false
    };

    const updatedMessages = [...messages, newMessage];
    setMessages(updatedMessages);
    setInputMessage('');
    setIsLoading(true);

    try {
      console.log('Sending message to Gemini...');
      
      // Prepare conversation history (excluding the initial greeting)
      const conversationHistory = messages.slice(1);

      const { data, error } = await supabase.functions.invoke('chat-with-gemini', {
        body: {
          message: inputMessage,
          conversationHistory: conversationHistory
        }
      });

      if (error) {
        console.error('Supabase function error:', error);
        throw new Error(error.message || 'Failed to get AI response');
      }

      if (!data.success) {
        throw new Error(data.error || 'AI response failed');
      }

      console.log('Received AI response:', data.response);

      const botResponse = {
        id: updatedMessages.length + 1,
        text: data.response,
        isBot: true
      };

      setMessages(prev => [...prev, botResponse]);

    } catch (error) {
      console.error('Error getting AI response:', error);
      
      const errorResponse = {
        id: updatedMessages.length + 1,
        text: "I'm sorry, I'm having trouble connecting right now. Please try again in a moment.",
        isBot: true
      };

      setMessages(prev => [...prev, errorResponse]);
    } finally {
      setIsLoading(false);
    }
  };

  const handleKeyPress = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      handleSendMessage();
    }
  };

  return (
    <div className="min-h-screen bg-[#111] flex flex-col">
      {/* Header */}
      <div className="bg-[#1a1a1a] border-b border-[#333] px-4 py-4">
        <div className="max-w-4xl mx-auto flex items-center justify-between">
          <div className="flex items-center space-x-4">
            <Button
              variant="ghost"
              size="sm"
              onClick={() => navigate(-1)}
              className="text-gray-300 hover:text-[--primary]"
            >
              <ArrowLeft className="w-4 h-4 mr-2" />
              Back
            </Button>
            <div className="flex items-center">
              <MessageCircle className="w-6 h-6 text-[--primary] mr-3" />
              <div>
                <h1 className="text-xl font-bold text-[--primary]">Veronica AI</h1>
                <p className="text-sm text-gray-400">Your AI Mentor & Assistant</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Chat Container */}
      <div className="flex-1 flex flex-col max-w-4xl mx-auto w-full px-4 py-6">
        <Card className="flex-1 bg-[#1a1a1a] border-[#333] flex flex-col">
          <CardContent className="flex-1 flex flex-col p-0">
            {/* Messages */}
            <div className="flex-1 overflow-y-auto p-6 space-y-4">
              {messages.map((message) => (
                <div
                  key={message.id}
                  className={`flex ${message.isBot ? 'justify-start' : 'justify-end'}`}
                >
                  <div
                    className={`max-w-[80%] p-4 rounded-lg ${
                      message.isBot
                        ? 'bg-[#333] text-gray-300'
                        : 'bg-[--primary]/20 text-[--primary]'
                    }`}
                  >
                    <p className="whitespace-pre-wrap">{message.text}</p>
                  </div>
                </div>
              ))}
              {isLoading && (
                <div className="flex justify-start">
                  <div className="bg-[#333] text-gray-300 p-4 rounded-lg flex items-center">
                    <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                    Veronica is thinking...
                  </div>
                </div>
              )}
              <div ref={messagesEndRef} />
            </div>

            {/* Input Area */}
            <div className="border-t border-[#333] p-6">
              <div className="flex gap-3">
                <textarea
                  value={inputMessage}
                  onChange={(e) => setInputMessage(e.target.value)}
                  onKeyPress={handleKeyPress}
                  placeholder="Ask Veronica anything..."
                  disabled={isLoading}
                  rows={3}
                  className="flex-1 p-3 rounded-lg bg-[#333] text-white border border-[#444] focus:border-[--primary] outline-none disabled:opacity-50 resize-none"
                />
                <Button 
                  onClick={handleSendMessage}
                  disabled={isLoading || !inputMessage.trim()}
                  className="px-6 bg-[--primary] hover:bg-[--primary]/80 text-[#111]"
                >
                  {isLoading ? (
                    <Loader2 className="w-5 h-5 animate-spin" />
                  ) : (
                    <Send className="w-5 h-5" />
                  )}
                </Button>
              </div>
              <p className="text-xs text-gray-500 mt-2">
                Press Enter to send, Shift+Enter for new line
              </p>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
