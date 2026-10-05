
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Search, Play, Star, Users, BookOpen } from "lucide-react";

export const Hero = () => {
  return (
    <section className="relative py-20 px-4 overflow-hidden">
      <div className="max-w-7xl mx-auto">
        <div className="grid lg:grid-cols-2 gap-12 items-center">
          {/* Left Content */}
          <div className="space-y-8">
            <div className="space-y-4">
              <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                Learn Skills for
                <span className="text-blue-600 block">Your Future</span>
              </h1>
              <p className="text-lg md:text-xl text-gray-600 max-w-xl">
                Join thousands of students learning with expert instructors. 
                Build real projects and advance your career with VidyaSathi.
              </p>
            </div>

            {/* Search Bar */}
            <div className="flex flex-col sm:flex-row gap-3 max-w-md">
              <div className="relative flex-1">
                <Search className="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                <Input 
                  placeholder="What do you want to learn?" 
                  className="pl-10 h-12 text-base border-gray-300"
                />
              </div>
              <Button size="lg" className="bg-blue-600 hover:bg-blue-700 h-12 px-8">
                Search
              </Button>
            </div>

            {/* Stats */}
            <div className="flex flex-wrap gap-6 pt-4">
              <div className="flex items-center space-x-2">
                <Users className="h-5 w-5 text-blue-600" />
                <span className="text-sm text-gray-600">50,000+ Students</span>
              </div>
              <div className="flex items-center space-x-2">
                <BookOpen className="h-5 w-5 text-blue-600" />
                <span className="text-sm text-gray-600">300+ Courses</span>
              </div>
              <div className="flex items-center space-x-2">
                <Star className="h-5 w-5 text-yellow-500 fill-current" />
                <span className="text-sm text-gray-600">4.8 Rating</span>
              </div>
            </div>
          </div>

          {/* Right Content - Video/Image */}
          <div className="relative">
            <div className="relative bg-gradient-to-br from-blue-100 to-indigo-200 rounded-2xl p-8 aspect-video flex items-center justify-center">
              <img 
                src="https://images.unsplash.com/photo-1649972904349-6e44c42644a7"
                alt="Student learning online"
                className="rounded-xl object-cover w-full h-full"
              />
              <div className="absolute inset-0 bg-black/20 rounded-xl flex items-center justify-center">
                <Button size="lg" className="bg-white/90 text-blue-600 hover:bg-white rounded-full p-4">
                  <Play className="h-8 w-8 fill-current" />
                </Button>
              </div>
            </div>
            
            {/* Floating Cards */}
            <div className="absolute -bottom-6 -left-6 bg-white rounded-lg shadow-lg p-4 border">
              <div className="flex items-center space-x-3">
                <div className="bg-green-100 rounded-full p-2">
                  <BookOpen className="h-4 w-4 text-green-600" />
                </div>
                <div>
                  <p className="text-sm font-semibold text-gray-900">Course Completed</p>
                  <p className="text-xs text-gray-600">Web Development</p>
                </div>
              </div>
            </div>
            
            <div className="absolute -top-6 -right-6 bg-white rounded-lg shadow-lg p-4 border">
              <div className="flex items-center space-x-3">
                <div className="bg-blue-100 rounded-full p-2">
                  <Users className="h-4 w-4 text-blue-600" />
                </div>
                <div>
                  <p className="text-sm font-semibold text-gray-900">Live Students</p>
                  <p className="text-xs text-gray-600">2,847 online now</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};
