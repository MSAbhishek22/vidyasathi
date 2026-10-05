
import { Navigation } from "@/components/Navigation";
import { Footer } from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Users, MessageCircle, Heart, Search, Plus, Info } from "lucide-react";

const communityStats = [
  {
    number: "3",
    label: "Total Posts",
    icon: <Users className="w-6 h-6" />
  },
  {
    number: "2", 
    label: "Discussions",
    icon: <MessageCircle className="w-6 h-6" />
  },
  {
    number: "1",
    label: "Appreciations", 
    icon: <Heart className="w-6 h-6" />
  }
];

export default function CommunityPage() {
  return (
    <div className="min-h-screen bg-[#111111] font-sans">
      <Navigation />
      
      <div className="container mx-auto px-6 py-16">
        {/* Header Section */}
        <div className="max-w-4xl mx-auto text-center mb-12">
          <h1 className="text-5xl font-bold text-white mb-6">
            VidyaSathi Community
          </h1>
          <p className="text-xl text-gray-300 mb-2">
            Connect with fellow students, share knowledge, ask questions, and build your network.
          </p>
          <p className="text-xl text-gray-300 mb-8">
            Our community is a space for collaboration and growth.
          </p>
          
          {/* Stats Cards */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {communityStats.map((stat, index) => (
              <div 
                key={index}
                className="bg-[#2a2d47] p-6 rounded-lg border border-[#3a3d5a]"
              >
                <div className="flex items-center justify-center mb-2 text-gray-400">
                  {stat.icon}
                </div>
                <div className="text-3xl font-bold text-white mb-1">{stat.number}</div>
                <div className="text-gray-400 text-sm">{stat.label}</div>
              </div>
            ))}
          </div>
          
          {/* Share Knowledge Button */}
          <Button className="bg-[#FF6B35] hover:bg-[#FF6B35]/90 text-white font-semibold px-8 py-3 rounded-lg mb-12">
            <Plus className="w-5 h-5 mr-2" />
            Share Your Knowledge
          </Button>
        </div>
        
        {/* Find Posts Section */}
        <div className="max-w-4xl mx-auto mb-12">
          <h2 className="text-2xl font-bold text-white mb-6">Find Posts</h2>
          <div className="flex gap-4">
            <div className="flex-1 relative">
              <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
              <Input 
                placeholder="Search by keyword, topic, or content..."
                className="bg-[#2a2d47] border-[#3a3d5a] text-white pl-12 py-3 text-lg"
              />
            </div>
            <Button className="bg-[#FFD600] text-[#111] hover:bg-[#FFD600]/90 font-semibold px-8 py-3">
              <Search className="w-5 h-5 mr-2" />
              Search
            </Button>
          </div>
        </div>
        
        {/* Community Guidelines */}
        <div className="max-w-4xl mx-auto">
          <div className="bg-[#1e1e2f] p-6 rounded-lg border border-[#333]">
            <div className="flex items-start">
              <Info className="w-6 h-6 text-blue-400 mr-3 mt-1 flex-shrink-0" />
              <div>
                <h3 className="text-xl font-bold text-white mb-2">Community Guidelines</h3>
                <p className="text-gray-300 leading-relaxed">
                  Be respectful, share valuable content, provide constructive feedback, and help each other grow. Together we build a supportive learning environment.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <Footer />
    </div>
  );
}
