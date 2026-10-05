
import { Card, CardContent, CardHeader, CardTitle } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Upload, Users, MessageSquare, BarChart } from 'lucide-react';

export default function SeniorDashboard() {
  const seniorActions = [
    {
      title: 'Upload Resources',
      description: 'Share notes, projects, and study materials',
      icon: <Upload className="w-8 h-8 text-[--primary]" />,
      action: 'Upload Files'
    },
    {
      title: 'Create Polls',
      description: 'Engage with the community through polls',
      icon: <BarChart className="w-8 h-8 text-[--primary]" />,
      action: 'Create Poll'
    },
    {
      title: 'Post Updates',
      description: 'Share important announcements and updates',
      icon: <MessageSquare className="w-8 h-8 text-[--primary]" />,
      action: 'Post Update'
    },
    {
      title: 'Mentor Students',
      description: 'Guide and support junior students',
      icon: <Users className="w-8 h-8 text-[--primary]" />,
      action: 'Start Mentoring'
    }
  ];

  return (
    <div className="min-h-screen bg-[#111] p-4">
      <div className="max-w-7xl mx-auto">
        <h1 className="text-3xl font-bold text-white mb-8">Senior Dashboard</h1>
        
        <div className="grid md:grid-cols-2 gap-6">
          {seniorActions.map((action, index) => (
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
