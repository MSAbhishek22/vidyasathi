
import { Navigation } from "@/components/Navigation";
import { Footer } from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { ArrowLeft, Star, Home, FileText, Video, Monitor, Users, Calendar, FileX } from "lucide-react";
import { useNavigate } from "react-router-dom";
import { usePdfResources } from "@/hooks/usePdfResources";
import { useVideoResources } from "@/hooks/useVideoResources";
import { useToast } from "@/hooks/use-toast";

export default function DSAPage() {
  const navigate = useNavigate();
  const { toast } = useToast();
  const { data: pdfResources, isLoading: isPdfLoading, error: pdfError } = usePdfResources('DSA');
  const { data: videoResources, isLoading: isVideoLoading, error: videoError } = useVideoResources('DSA');

  const handleViewPdfResources = () => {
    if (isPdfLoading) {
      toast({
        title: "Loading...",
        description: "Please wait while we fetch the PDF resources.",
      });
      return;
    }

    if (pdfError) {
      toast({
        title: "Error",
        description: "Failed to load PDF resources. Please try again.",
        variant: "destructive",
      });
      return;
    }

    if (pdfResources && pdfResources.length > 0) {
      // Open the first PDF resource (DSA Complete Notes)
      window.open(pdfResources[0].pdf_url, '_blank');
    } else {
      toast({
        title: "No Resources Found",
        description: "No PDF resources are currently available for DSA.",
        variant: "destructive",
      });
    }
  };

  const handleViewVideoTutorials = () => {
    if (isVideoLoading) {
      toast({
        title: "Loading...",
        description: "Please wait while we fetch the video tutorials.",
      });
      return;
    }

    if (videoError) {
      toast({
        title: "Error",
        description: "Failed to load video tutorials. Please try again.",
        variant: "destructive",
      });
      return;
    }

    if (videoResources && videoResources.length > 0) {
      // Open the first video resource (DSA YouTube playlist)
      window.open(videoResources[0].video_url, '_blank');
    } else {
      toast({
        title: "No Videos Found",
        description: "No video tutorials are currently available for DSA.",
        variant: "destructive",
      });
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
              Data Structures & Algorithms
            </h1>
            <div className="flex items-center mb-4">
              <div className="flex items-center mr-6">
                <Star className="w-5 h-5 text-[#FFD600] fill-current mr-2" />
                <span className="text-white font-semibold text-lg">4.9</span>
                <span className="text-gray-400 ml-2">• 120 students</span>
              </div>
            </div>
            <p className="text-gray-300 text-lg leading-relaxed mb-6">
              Master the fundamentals of DSA with comprehensive notes and practice problems.
            </p>
            
            {/* Info Box */}
            <div className="bg-[#2a2d47] p-4 rounded-lg border border-[#3a3d5a] flex items-start">
              <div className="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                <span className="text-white text-sm font-bold">i</span>
              </div>
              <p className="text-gray-300 leading-relaxed">
                Choose one of the resource options below to strengthen your DSA knowledge. We recommend starting with the PDF guides for theory and then exploring the video tutorials for implementation details.
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
                  <p className="text-gray-400">Comprehensive guides and practice problems</p>
                </div>
              </div>
              
              <div className="space-y-3 mb-6">
                <div className="flex items-center">
                  <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <span className="text-white text-xs">✓</span>
                  </div>
                  <span className="text-gray-300">Complete theory on arrays, linked lists, trees, graphs & more</span>
                </div>
                <div className="flex items-center">
                  <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <span className="text-white text-xs">✓</span>
                  </div>
                  <span className="text-gray-300">Big O notation and algorithm complexity analysis</span>
                </div>
                <div className="flex items-center">
                  <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <span className="text-white text-xs">✓</span>
                  </div>
                  <span className="text-gray-300">100+ practice problems with detailed solutions</span>
                </div>
              </div>
              
              <Button 
                onClick={handleViewPdfResources}
                className="w-full bg-[#FFD600] text-[#111] hover:bg-[#FFD600]/90 font-semibold py-3"
                disabled={isPdfLoading}
              >
                <FileText className="w-5 h-5 mr-2" />
                {isPdfLoading ? "Loading..." : "View PDF Resources"}
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
                  <p className="text-gray-400">Visual explanations and coding examples</p>
                </div>
              </div>
              
              <div className="space-y-3 mb-6">
                <div className="flex items-center">
                  <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <span className="text-white text-xs">✓</span>
                  </div>
                  <span className="text-gray-300">Visual explanations of complex data structures</span>
                </div>
                <div className="flex items-center">
                  <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <span className="text-white text-xs">✓</span>
                  </div>
                  <span className="text-gray-300">Step-by-step algorithm implementations</span>
                </div>
                <div className="flex items-center">
                  <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-3">
                    <span className="text-white text-xs">✓</span>
                  </div>
                  <span className="text-gray-300">Competitive programming techniques and tips</span>
                </div>
              </div>
              
              <Button 
                onClick={handleViewVideoTutorials}
                className="w-full bg-[#FFD600] text-[#111] hover:bg-[#FFD600]/90 font-semibold py-3"
                disabled={isVideoLoading}
              >
                <Video className="w-5 h-5 mr-2" />
                {isVideoLoading ? "Loading..." : "View YouTube Tutorials"}
              </Button>
            </div>
          </div>

          {/* Additional DSA Resources */}
          <div className="mb-12">
            <h2 className="text-3xl font-bold text-white mb-8">Additional DSA Resources</h2>
            <div className="grid md:grid-cols-2 gap-6">
              <div className="bg-[#1e1e2f] p-6 rounded-xl border border-[#333] hover:border-[#FFD600]/50 transition-colors">
                <div className="flex items-center mb-3">
                  <div className="p-2 bg-[#FFD600]/10 rounded-lg mr-3">
                    <Monitor className="w-6 h-6 text-[#FFD600]" />
                  </div>
                  <div>
                    <h3 className="text-xl font-bold text-white">Coding Challenges</h3>
                    <p className="text-gray-400 text-sm">Practice problems to test your skills</p>
                  </div>
                </div>
              </div>

              <div className="bg-[#1e1e2f] p-6 rounded-xl border border-[#333] hover:border-[#FFD600]/50 transition-colors">
                <div className="flex items-center mb-3">
                  <div className="p-2 bg-[#FFD600]/10 rounded-lg mr-3">
                    <FileX className="w-6 h-6 text-[#FFD600]" />
                  </div>
                  <div>
                    <h3 className="text-xl font-bold text-white">DSA Projects</h3>
                    <p className="text-gray-400 text-sm">Real-world applications of algorithms</p>
                  </div>
                </div>
              </div>

              <div className="bg-[#1e1e2f] p-6 rounded-xl border border-[#333] hover:border-[#FFD600]/50 transition-colors">
                <div className="flex items-center mb-3">
                  <div className="p-2 bg-[#FFD600]/10 rounded-lg mr-3">
                    <Users className="w-6 h-6 text-[#FFD600]" />
                  </div>
                  <div>
                    <h3 className="text-xl font-bold text-white">Study Groups</h3>
                    <p className="text-gray-400 text-sm">Join peers to solve problems together</p>
                  </div>
                </div>
              </div>

              <div className="bg-[#1e1e2f] p-6 rounded-xl border border-[#333] hover:border-[#FFD600]/50 transition-colors">
                <div className="flex items-center mb-3">
                  <div className="p-2 bg-[#FFD600]/10 rounded-lg mr-3">
                    <Calendar className="w-6 h-6 text-[#FFD600]" />
                  </div>
                  <div>
                    <h3 className="text-xl font-bold text-white">Interview Prep</h3>
                    <p className="text-gray-400 text-sm">Prepare for technical interviews</p>
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
