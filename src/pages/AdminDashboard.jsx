
import { Card, CardContent, CardHeader, CardTitle } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Users, Shield, FileText, Settings } from 'lucide-react';

export default function AdminDashboard() {
  const adminActions = [
    {
      title: 'Manage Users',
      description: 'View and manage all registered users',
      icon: <Users className="w-8 h-8 text-[--primary]" />,
      action: 'Manage Users'
    },
    {
      title: 'Content Moderation',
      description: 'Review and moderate user-generated content',
      icon: <Shield className="w-8 h-8 text-[--primary]" />,
      action: 'Moderate Content'
    },
    {
      title: 'Weekly Updates',
      description: 'Post platform-wide announcements',
      icon: <FileText className="w-8 h-8 text-[--primary]" />,
      action: 'Create Update'
    },
    {
      title: 'System Settings',
      description: 'Configure platform settings and features',
      icon: <Settings className="w-8 h-8 text-[--primary]" />,
      action: 'Open Settings'
    }
  ];

  return (
    <div className="min-h-screen bg-[#111] p-4">
      <div className="max-w-7xl mx-auto">
        <h1 className="text-3xl font-bold text-white mb-8">Admin Dashboard</h1>
        
        <div className="grid md:grid-cols-2 gap-6">
          {adminActions.map((action, index) => (
            <Card key={index} className="bg-[#1a1a1a] border-[#333] hover:border-[--primary]/50 transition-all">
              <CardHeader>
                <div className="flex items-center mb-4">
                  {action.icon}
                </div>
                <CardTitle className="text-white">{action.title}</CardTitle>
              </CardHeader>
              <CardContent>
                <p className="text-gray-400 mb-4">{action.description}</p>
                <Button className="w-full">
                  {action.action}
                </Button>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </div>
  );
}
