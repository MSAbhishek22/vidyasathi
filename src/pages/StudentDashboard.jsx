
import { useState, useEffect } from 'react';
import { Card, CardContent } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { BookOpen, FileText, Briefcase, Download, Calendar, ArrowLeft, Users } from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import { Navigation } from "@/components/Navigation";
import { Footer } from "@/components/Footer";

export default function StudentDashboard() {
  const [weeklyUpdates, setWeeklyUpdates] = useState([]);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    // This will be replaced with Supabase data fetching once integrated
    console.log('Resources page ready for Supabase integration');
    setWeeklyUpdates([
      {
        id: 1,
        title: 'Welcome to VidyaSathi Resources!',
        content: 'Access all your learning materials in one place.',
        timestamp: new Date()
      }
    ]);
    setLoading(false);
  }, []);

  const resourcesData = [
    {
      title: 'Previous Year Questions',
      description: 'Get access to carefully curated PYQs to ace your exams with flying colors.',
      icon: <FileText className="w-8 h-8 text-[#FFD600]" />,
      count: '500+'
    },
    {
      title: 'Notes from Toppers',
      description: 'Learn from the best - get access to comprehensive notes from top-performing seniors.',
      icon: <BookOpen className="w-8 h-8 text-[#FFD600]" />,
      count: '200+'
    },
    {
      title: 'Resume Tools',
      description: 'Build ATS-friendly resumes with expert guidance and industry-specific templates.',
      icon: <Download className="w-8 h-8 text-[#FFD600]" />,
      count: '24/7'
    },
    {
      title: 'Mentorship',
      description: 'Connect directly with seniors who have walked the path before you.',
      icon: <Users className="w-8 h-8 text-[#FFD600]" />,
      count: '1:1'
    },
    {
      title: 'Real Projects',
      description: 'Gain practical experience through industry-relevant projects that boost your portfolio.',
      icon: <Briefcase className="w-8 h-8 text-[#FFD600]" />,
      count: 'Live'
    },
    {
      title: 'Q&A Forum',
      description: 'Get your doubts cleared by a community of helpful seniors and peers.',
      icon: <Calendar className="w-8 h-8 text-[#FFD600]" />,
      count: '24/7'
    }
  ];

  return (
    <div className="min-h-screen bg-[#111]">
      <Navigation />
      
      {/* Back Button */}
      <div className="container mx-auto px-6 pt-8">
        <Button
          onClick={() => navigate('/')}
          variant="ghost"
          className="text-white hover:text-[#FFD600] mb-8"
        >
          <ArrowLeft className="w-5 h-5 mr-2" />
          Back to Home
        </Button>
      </div>
      
      <div className="container mx-auto px-6 py-8 mb-20">
        {/* Page Heading */}
        <div className="text-center mb-16">
          <h1 className="text-5xl font-bold text-white">Resources</h1>
        </div>
        
        {/* Resources Grid */}
        <div className="max-w-7xl mx-auto">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-items-center">
            {resourcesData.map((resource, index) => (
              <Card 
                key={index} 
                className="w-full max-w-sm bg-[#2a2d47] border border-[#3a3d5a] hover:border-[#FFD600]/50 transition-all duration-300 hover:transform hover:scale-105"
              >
                <CardContent className="p-6">
                  <div className="flex flex-col h-full">
                    {/* Header with Icon and Count */}
                    <div className="flex items-start justify-between mb-4">
                      <div className="p-3 bg-[#FFD600]/10 rounded-lg">
                        {resource.icon}
                      </div>
                      <span className="text-[#FFD600] font-bold text-lg">
                        {resource.count}
                      </span>
                    </div>
                    
                    {/* Title */}
                    <h3 className="text-xl font-bold text-white mb-3">
                      {resource.title}
                    </h3>
                    
                    {/* Description */}
                    <p className="text-gray-300 text-sm leading-relaxed mb-6 flex-grow">
                      {resource.description}
                    </p>
                    
                    {/* Access Button */}
                    <Button 
                      className="w-full bg-[#FFD600] text-[#111] hover:bg-[#FFD600]/90 font-semibold py-2 rounded-lg transition-all duration-300"
                      variant="default"
                    >
                      Access Now
                    </Button>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </div>
      
      <Footer />
    </div>
  );
}
