
import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '../components/ui/avatar';
import { Navigation } from '../components/Navigation';
import { Footer } from '../components/Footer';
import { Home, User, Edit, FileText, MessageCircle, Users, BookOpen, Eye, Plus, ArrowRight } from 'lucide-react';
import { useAuth } from '../hooks/useAuth';

export default function ProfilePage() {
  const { user, userRole } = useAuth();
  const [activeTab, setActiveTab] = useState('posts');

  const stats = [
    { label: 'Posts', value: '0', color: 'text-white' },
    { label: 'Likes', value: '0', color: 'text-red-400' },
    { label: 'Comments', value: '0', color: 'text-blue-400' }
  ];

  const quickActions = [
    { label: 'View Profile', icon: Eye, variant: 'default' as const },
    { label: 'Edit Profile', icon: Edit, variant: 'outline' as const }
  ];

  const menuItems = [
    { label: 'My Posts', icon: FileText, count: '0', active: true },
    { label: 'Interactions', icon: MessageCircle, count: '0', subtitle: '0 likes, 0 comments' },
    { label: 'Community', icon: Users, count: '3+', subtitle: 'Join discussions', action: true }
  ];

  const communityDiscussions = [
    { id: 1, title: 'yoooooo', author: 'aayu', date: 'Apr 26', avatar: 'A' },
    { id: 2, title: 'Testing 2', author: 'The Wrook', date: 'Apr 26', avatar: 'T' },
    { id: 3, title: 'lets rock', author: 'The Wrook', date: 'Apr 26', avatar: 'T' }
  ];

  return (
    <div className="min-h-screen bg-[#111]">
      <Navigation />
      
      {/* Header */}
      <header className="bg-[#1a1a1a] border-b border-[#333] px-4 py-4">
        <div className="max-w-7xl mx-auto flex items-center justify-between">
          <div className="flex items-center space-x-8">
            <div className="flex items-center space-x-2">
              <Home className="w-5 h-5 text-[#FFD600]" />
              <span className="text-white font-medium">Home Page</span>
            </div>
            <div className="flex items-center space-x-2">
              <User className="w-5 h-5 text-[#FFD600]" />
              <span className="text-white font-medium">My Profile</span>
            </div>
            <div className="flex items-center space-x-2">
              <Edit className="w-5 h-5 text-[#FFD600]" />
              <span className="text-white font-medium">Create New Post</span>
            </div>
            <div className="flex items-center space-x-2">
              <BookOpen className="w-5 h-5 text-[#FFD600]" />
              <span className="text-white font-medium">Become a Mentor</span>
            </div>
          </div>
          <div className="text-white font-medium">
            Welcome, {userRole === 'student' ? 'Student' : userRole || 'User'}
          </div>
        </div>
      </header>

      <div className="max-w-7xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
        {/* Left Sidebar - Profile Info */}
        <div className="lg:col-span-1">
          <Card className="bg-[#1a1a1a] border-[#333] mb-6">
            <CardContent className="p-6 text-center">
              <Avatar className="w-24 h-24 mx-auto mb-4">
                <AvatarImage src="" />
                <AvatarFallback className="bg-white text-black text-2xl font-bold">
                  {user?.email?.charAt(0).toUpperCase() || 'R'}
                </AvatarFallback>
              </Avatar>
              <h2 className="text-white text-xl font-bold mb-1">
                {user?.email?.split('@')[0] || 'Raj'}
              </h2>
              <p className="text-gray-400 mb-6">{userRole || 'Student'}</p>
              
              {/* Stats */}
              <div className="flex justify-between mb-6">
                {stats.map((stat, index) => (
                  <div key={index} className="text-center">
                    <div className={`text-xl font-bold ${stat.color}`}>{stat.value}</div>
                    <div className="text-gray-400 text-sm">{stat.label}</div>
                  </div>
                ))}
              </div>

              {/* Quick Actions */}
              <div className="space-y-3 mb-6">
                <h3 className="text-white font-medium text-left">QUICK ACTIONS</h3>
                {quickActions.map((action, index) => (
                  <Button
                    key={index}
                    variant={action.variant}
                    className="w-full justify-start"
                  >
                    <action.icon className="w-4 h-4 mr-2" />
                    {action.label}
                  </Button>
                ))}
                <Button variant="outline" className="w-full justify-start">
                  <Plus className="w-4 h-4 mr-2" />
                  Create Post
                </Button>
              </div>

              {/* Menu */}
              <div>
                <h3 className="text-white font-medium text-left mb-3">MENU</h3>
                {/* Menu items would go here */}
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Center Content */}
        <div className="lg:col-span-2">
          {/* Welcome Banner */}
          <Card className="bg-gradient-to-r from-purple-600 to-blue-600 border-none mb-6">
            <CardContent className="p-6">
              <div className="flex items-center mb-4">
                <div className="bg-white/20 p-2 rounded mr-3">
                  <BookOpen className="w-6 h-6 text-white" />
                </div>
                <div>
                  <h2 className="text-white text-xl font-bold">Welcome back, {user?.email?.split('@')[0] || 'Raj'}!</h2>
                  <p className="text-white/80">Continue your learning journey today</p>
                </div>
              </div>
              <div className="flex space-x-3">
                <Button className="bg-white text-purple-600 hover:bg-white/90">
                  <Plus className="w-4 h-4 mr-2" />
                  Create Post
                </Button>
                <Button variant="outline" className="border-white text-white hover:bg-white/10">
                  <Users className="w-4 h-4 mr-2" />
                  Browse Community
                </Button>
              </div>
            </CardContent>
          </Card>

          {/* Menu Cards */}
          <div className="grid grid-cols-3 gap-4 mb-6">
            {menuItems.map((item, index) => (
              <Card key={index} className="bg-[#1a1a1a] border-[#333] hover:border-[#FFD600]/50 transition-colors cursor-pointer">
                <CardContent className="p-4">
                  <div className="flex items-center justify-between mb-2">
                    <item.icon className="w-5 h-5 text-[#FFD600]" />
                    <span className="text-[#FFD600] font-bold">{item.count}</span>
                  </div>
                  <h3 className="text-white font-medium mb-1">{item.label}</h3>
                  {item.subtitle && (
                    <p className="text-gray-400 text-sm">{item.subtitle}</p>
                  )}
                  {item.action && (
                    <Button variant="link" className="p-0 h-auto text-[#FFD600] text-sm">
                      Join discussions <ArrowRight className="w-3 h-3 ml-1" />
                    </Button>
                  )}
                  {item.label === 'My Posts' && (
                    <Button variant="link" className="p-0 h-auto text-[#FFD600] text-sm">
                      View all <ArrowRight className="w-3 h-3 ml-1" />
                    </Button>
                  )}
                </CardContent>
              </Card>
            ))}
          </div>

          {/* My Recent Posts */}
          <Card className="bg-[#1a1a1a] border-[#333]">
            <CardHeader>
              <div className="flex items-center justify-between">
                <CardTitle className="text-white">My Recent Posts</CardTitle>
                <Button variant="link" className="text-[#FFD600] p-0">
                  View all <ArrowRight className="w-4 h-4 ml-1" />
                </Button>
              </div>
            </CardHeader>
            <CardContent>
              <div className="text-center py-8">
                <p className="text-gray-400 mb-4">You haven't created any posts yet</p>
                <Button className="bg-[#FFD600] text-black hover:bg-[#FFD600]/90">
                  <Plus className="w-4 h-4 mr-2" />
                  Create Your First Post
                </Button>
              </div>
            </CardContent>
          </Card>

          {/* Recommended Learning Resources */}
          <div className="mt-6">
            <h3 className="text-white text-lg font-bold mb-4">Recommended Learning Resources</h3>
            <Card className="bg-gradient-to-r from-blue-600 to-purple-600 border-none">
              <CardContent className="p-6 text-center">
                <h4 className="text-white text-xl font-bold mb-2">New Features Available!</h4>
                <p className="text-white/80 mb-4">Discover the latest tools and resources to enhance your learning experience</p>
                <Button className="bg-white text-blue-600 hover:bg-white/90">
                  Explore Now
                </Button>
              </CardContent>
            </Card>
          </div>
        </div>

        {/* Right Sidebar */}
        <div className="lg:col-span-1">
          {/* Community Discussions */}
          <Card className="bg-[#1a1a1a] border-[#333] mb-6">
            <CardHeader>
              <div className="flex items-center justify-between">
                <CardTitle className="text-white">Community Discussions</CardTitle>
                <Button variant="link" className="text-[#FFD600] p-0 text-sm">
                  View all <ArrowRight className="w-3 h-3 ml-1" />
                </Button>
              </div>
            </CardHeader>
            <CardContent>
              <div className="space-y-4">
                {communityDiscussions.map((discussion) => (
                  <div key={discussion.id} className="flex items-start space-x-3">
                    <Avatar className="w-8 h-8">
                      <AvatarFallback className="bg-[#FFD600] text-black text-sm">
                        {discussion.avatar}
                      </AvatarFallback>
                    </Avatar>
                    <div className="flex-1 min-w-0">
                      <h4 className="text-white font-medium text-sm">{discussion.title}</h4>
                      <p className="text-gray-400 text-xs">by {discussion.author} • {discussion.date}</p>
                      <Button variant="link" className="text-[#FFD600] p-0 h-auto text-xs mt-1">
                        Join discussion <ArrowRight className="w-3 h-3 ml-1" />
                      </Button>
                    </div>
                  </div>
                ))}
              </div>
              <Button className="w-full mt-4 bg-[#FFD600] text-black hover:bg-[#FFD600]/90">
                <Users className="w-4 h-4 mr-2" />
                Browse All Discussions
              </Button>
            </CardContent>
          </Card>

          {/* Announcements */}
          <Card className="bg-[#1a1a1a] border-[#333]">
            <CardHeader>
              <CardTitle className="text-white">Announcements</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="border-l-4 border-blue-500 pl-4">
                <h4 className="text-white font-medium mb-1">New Features Available!</h4>
                <p className="text-gray-400 text-sm mb-2">We've added new learning tools and resources to help you succeed in your studies.</p>
                <Button variant="link" className="text-[#FFD600] p-0 text-sm">
                  Learn More <ArrowRight className="w-3 h-3 ml-1" />
                </Button>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>

      <Footer />
    </div>
  );
}
