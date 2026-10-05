
import { useState, useEffect } from 'react';
import { Button } from './ui/button';
import { Card, CardContent, CardHeader, CardTitle } from './ui/card';
import { MessageCircle, X, Send, Loader2 } from 'lucide-react';
import { supabase } from '@/integrations/supabase/client';

export const Veronica = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [messages, setMessages] = useState([
    {
      id: 1,
      text: "Hi! I'm Veronica, your AI mentor. How can I help you today?",
      isBot: true
    }
  ]);
  const [inputMessage, setInputMessage] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  // Listen for custom event to open Veronica
  useEffect(() => {
    const handleOpenVeronica = () => {
      setIsOpen(true);
    };

    window.addEventListener('openVeronica', handleOpenVeronica);
    return () => {
      window.removeEventListener('openVeronica', handleOpenVeronica);
    };
  }, []);

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
      
      // Prepare conversation history (excluding the current message)
      const conversationHistory = messages.slice(1); // Remove the initial greeting

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

  if (!isOpen) {
    return (
      <div className="fixed bottom-6 right-6 z-50">
        <Button
          onClick={() => setIsOpen(true)}
          className="veronica-trigger rounded-full w-16 h-16 bg-[--primary] hover:bg-[--primary]/80"
        >
          <MessageCircle className="w-8 h-8 text-[#111]" />
        </Button>
      </div>
    );
  }

  return (
    <div className="fixed bottom-6 right-6 z-50 w-80">
      <Card className="bg-[#1a1a1a] border-[#333]">
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-4">
          <CardTitle className="text-[--primary] flex items-center">
            <MessageCircle className="w-5 h-5 mr-2" />
            Veronica AI
          </CardTitle>
          <Button
            variant="ghost"
            size="sm"
            onClick={() => setIsOpen(false)}
          >
            <X className="w-4 h-4" />
          </Button>
        </CardHeader>
        <CardContent>
          <div className="h-64 overflow-y-auto mb-4 space-y-2">
            {messages.map((message) => (
              <div
                key={message.id}
                className={`p-2 rounded-lg text-sm ${
                  message.isBot
                    ? 'bg-[#333] text-gray-300'
                    : 'bg-[--primary]/20 text-[--primary] ml-4'
                }`}
              >
                {message.text}
              </div>
            ))}
            {isLoading && (
              <div className="bg-[#333] text-gray-300 p-2 rounded-lg text-sm flex items-center">
                <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                Veronica is thinking...
              </div>
            )}
          </div>
          <div className="flex gap-2">
            <input
              type="text"
              value={inputMessage}
              onChange={(e) => setInputMessage(e.target.value)}
              onKeyPress={(e) => e.key === 'Enter' && handleSendMessage()}
              placeholder="Ask Veronica..."
              disabled={isLoading}
              className="flex-1 p-2 rounded-md bg-[#333] text-white text-sm border border-[#444] focus:border-[--primary] outline-none disabled:opacity-50"
            />
            <Button 
              size="sm" 
              onClick={handleSendMessage}
              disabled={isLoading || !inputMessage.trim()}
            >
              {isLoading ? (
                <Loader2 className="w-4 h-4 animate-spin" />
              ) : (
                <Send className="w-4 h-4" />
              )}
            </Button>
          </div>
        </CardContent>
      </Card>
    </div>
  );
};
