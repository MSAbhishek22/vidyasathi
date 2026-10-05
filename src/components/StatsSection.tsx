
import { Users, BookOpen, Award, Clock } from "lucide-react";

export const StatsSection = () => {
  const stats = [
    {
      icon: Users,
      value: "50,000+",
      label: "Active Students",
      description: "Learning every day"
    },
    {
      icon: BookOpen,
      value: "300+",
      label: "Expert Courses",
      description: "In various domains"
    },
    {
      icon: Award,
      value: "95%",
      label: "Success Rate",
      description: "Student satisfaction"
    },
    {
      icon: Clock,
      value: "24/7",
      label: "Support",
      description: "Always available"
    }
  ];

  return (
    <section className="py-16 bg-white">
      <div className="max-w-7xl mx-auto px-4">
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Why Choose VidyaSathi?
          </h2>
          <p className="text-lg text-gray-600 max-w-2xl mx-auto">
            Join thousands of learners who trust us for their skill development journey
          </p>
        </div>
        
        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          {stats.map((stat, index) => {
            const IconComponent = stat.icon;
            return (
              <div key={index} className="text-center group">
                <div className="bg-blue-50 rounded-full p-4 w-16 h-16 mx-auto mb-4 group-hover:bg-blue-100 transition-colors">
                  <IconComponent className="h-8 w-8 text-blue-600 mx-auto" />
                </div>
                <div className="text-3xl font-bold text-gray-900 mb-2">{stat.value}</div>
                <div className="text-lg font-semibold text-gray-800 mb-1">{stat.label}</div>
                <div className="text-sm text-gray-600">{stat.description}</div>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
};
