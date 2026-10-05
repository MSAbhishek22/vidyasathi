
import { Navigation } from "@/components/Navigation";
import { Footer } from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { ArrowLeft, Star, Home, FileText, Video, Monitor, Users, BookOpen, Library } from "lucide-react";
import { useNavigate } from "react-router-dom";
import { usePdfResources } from "@/hooks/usePdfResources";
import { useVideoResources } from "@/hooks/useVideoResources";

export default function WebDevBasicsPage() {
  const navigate = useNavigate();
  const { data: pdfResources } = usePdfResources('Web Development');
  const { data: videoResources } = useVideoResources('Web Development');

  const handlePdfResourcesClick = () => {
    const webDevPdf = pdfResources?.[0];
    if (webDevPdf) {
      window.open(webDevPdf.pdf_url, '_blank');
    }
  };

  const handleVideoResourcesClick = () => {
    const webDevVideo = videoResources?.[0];
    if (webDevVideo) {
      window.open(webDevVideo.video_url, '_blank');
    }
  };

  return (
    <div className="min-h-screen bg-[#111111] font-sans">
      <Navigation />
      
      <div className="container mx-auto px-6 py-8">
        {/* Header with Back and Dashboard buttons */}
        <div className="flex justify-between items-center mb-8">
          <Button
            onClick={() => navigate('/resources')}
            variant="ghost"
            className="text-white hover:text-[#FFD600] flex items-center"
          >
            <ArrowLeft className="w-5 h-5 mr-2" />
            Back to Resources
          </Button>
          <Button
            onClick={() => navigate('/dashboard')}
            variant="ghost"
            className="text-white hover:text-[#FFD600] flex items-center"
          >
            <Home className="w-5 h-5 mr-2" />
            Dashboard
          </Button>
        </div>

        {/* Main Content */}
        <div className="max-w-4xl mx-auto">
          {/* Course Header */}
          <div className="bg-[#1e1e2f] p-8 rounded-xl border border-[#333] mb-8">
            <h1 className="text-4xl font-bold text-white mb-4">
              Web Development Basics
            </h1>
            <div className="flex items-center mb-4">
              <div className="flex items-center mr-6">
                <Star className="w-5 h-5 text-[#FFD600] fill-current mr-2" />
                <span className="text-white font-semibold text-lg">4.7</span>
                <span className="text-gray-400 ml-2">• 64 students</span>
              </div>
            </div>
            <p className="text-gray-300 text-lg leading-relaxed mb-6">
              Learn HTML, CSS, and JavaScript from scratch with hands-on projects.
            </p>
            
            {/* Info Box */}
            <div className="bg-[#2a2d47] p-4 rounded-lg border border-[#3a3d5a] flex items-start">
              <div className="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                <span className="text-white text-sm font-bold">i</span>
              </div>
              <p className="text-gray-300 leading-relaxed">
                Choose one of the resource options below to begin your web development journey. We recommend starting with the PDF guides and then exploring the video tutorials for a comprehensive learning experience.
              </p>
            </div>
          </div>

          {/* Resource Options */}
          <div className="grid md:grid-cols-2 gap-8 mb-12">
            {/* PDF Learning Materials */}
            <div className="bg-[#1e1e2f] p-8 rounded-xl border border-[#333]">
              <div className="flex items-center mb-6">
                <div className="p-3 bg-[#FFD600]/10 rounded-lg mr-4">
                  <FileText className="w-8 h-8 text-[#FFD600]" />
                </div>
                <div>
                  <h3 className="text-2xl font-bold text-white">PDF Learning Materials</h3>
                  <p className="text-gray-400">Comprehensive guides and reference materials</p>
                </div>
              </div>
              
              <div className="space-y-3 mb-6">
                <div className="flex items-center">
                  <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <span className="text-white text-xs">✓</span>
                  </div>
                  <span className="text-gray-300">Step-by-step HTML5, CSS3 & JavaScript tutorials</span>
                </div>
                <div className="flex items-center">
                  <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <span className="text-white text-xs">✓</span>
                  </div>
                  <span className="text-gray-300">Printable cheat sheets for quick reference</span>
                </div>
                <div className="flex items-center">
                  <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <span className="text-white text-xs">✓</span>
                  </div>
                  <span className="text-gray-300">Hands-on coding exercises with solutions</span>
                </div>
              </div>
              
              <Button 
                onClick={handlePdfResourcesClick}
                className="w-full bg-[#FFD600] text-[#111] hover:bg-[#FFD600]/90 font-semibold py-3"
              >
                <BookOpen className="w-5 h-5 mr-2" />
                View PDF Resources
              </Button>
            </div>

            {/* Video Tutorials */}
            <div className="bg-[#1e1e2f] p-8 rounded-xl border border-[#333]">
              <div className="flex items-center mb-6">
                <div className="p-3 bg-[#FFD600]/10 rounded-lg mr-4">
                  <Video className="w-8 h-8 text-[#FFD600]" />
                </div>
                <div>
                  <h3 className="text-2xl font-bold text-white">Video Tutorials</h3>
                  <p className="text-gray-400">Watch and code along with experts</p>
                </div>
              </div>
              
              <div className="space-y-3 mb-6">
                <div className="flex items-center">
                  <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <span className="text-white text-xs">✓</span>
                  </div>
                  <span className="text-gray-300">100+ video tutorials from beginner to advanced</span>
                </div>
                <div className="flex items-center">
                  <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <span className="text-white text-xs">✓</span>
                  </div>
                  <span className="text-gray-300">Visual demonstrations of core concepts</span>
                </div>
                <div className="flex items-center">
                  <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <span className="text-white text-xs">✓</span>
                  </div>
                  <span className="text-gray-300">Complete projects with code-along sessions</span>
                </div>
              </div>
              
              <Button 
                onClick={handleVideoResourcesClick}
                className="w-full bg-[#FFD600] text-[#111] hover:bg-[#FFD600]/90 font-semibold py-3"
              >
                <Video className="w-5 h-5 mr-2" />
                View YouTube Tutorials
              </Button>
            </div>
          </div>

          {/* Additional Resources */}
          <div className="mb-12">
            <h2 className="text-3xl font-bold text-white mb-8">Additional Resources</h2>
            <div className="grid md:grid-cols-2 gap-6">
              <div className="bg-[#1e1e2f] p-6 rounded-xl border border-[#333] hover:border-[#FFD600]/50 transition-colors">
                <div className="flex items-center mb-3">
                  <div className="p-2 bg-[#FFD600]/10 rounded-lg mr-3">
                    <Monitor className="w-6 h-6 text-[#FFD600]" />
                  </div>
                  <div>
                    <h3 className="text-xl font-bold text-white">Practice Exercises</h3>
                    <p className="text-gray-400 text-sm">Coding challenges to test your skills</p>
                  </div>
                </div>
              </div>

              <div className="bg-[#1e1e2f] p-6 rounded-xl border border-[#333] hover:border-[#FFD600]/50 transition-colors">
                <div className="flex items-center mb-3">
                  <div className="p-2 bg-[#FFD600]/10 rounded-lg mr-3">
                    <FileText className="w-6 h-6 text-[#FFD600]" />
                  </div>
                  <div>
                    <h3 className="text-xl font-bold text-white">Sample Projects</h3>
                    <p className="text-gray-400 text-sm">Real-world project ideas with code</p>
                  </div>
                </div>
              </div>

              <div className="bg-[#1e1e2f] p-6 rounded-xl border border-[#333] hover:border-[#FFD600]/50 transition-colors">
                <div className="flex items-center mb-3">
                  <div className="p-2 bg-[#FFD600]/10 rounded-lg mr-3">
                    <Users className="w-6 h-6 text-[#FFD600]" />
                  </div>
                  <div>
                    <h3 className="text-xl font-bold text-white">Community Forum</h3>
                    <p className="text-gray-400 text-sm">Discuss topics and get help</p>
                  </div>
                </div>
              </div>

              <div className="bg-[#1e1e2f] p-6 rounded-xl border border-[#333] hover:border-[#FFD600]/50 transition-colors">
                <div className="flex items-center mb-3">
                  <div className="p-2 bg-[#FFD600]/10 rounded-lg mr-3">
                    <Library className="w-6 h-6 text-[#FFD600]" />
                  </div>
                  <div>
                    <h3 className="text-xl font-bold text-white">Resource Library</h3>
                    <p className="text-gray-400 text-sm">More books and learning materials</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <Footer />
    </div>
  );
}
