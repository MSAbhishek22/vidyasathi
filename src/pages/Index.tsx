import { Navigation } from "@/components/Navigation";
import { Footer } from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { BookOpen, Users, Bot, Star, TrendingUp, Award, FileText, MessageCircle, Target, Heart, Lightbulb, Coffee } from "lucide-react";
import { useAuth } from "../hooks/useAuth";
import { useNavigate } from "react-router-dom";
import { useVideoResources } from "@/hooks/useVideoResources";
import { useScrollAnimation } from "@/hooks/useScrollAnimation";

const features = [
  {
    icon: <Star className="w-8 h-8 text-[#FFD600]" />,
    title: "1000+",
    description: "Active Students"
  },
  {
    icon: <TrendingUp className="w-8 h-8 text-[#FFD600]" />,
    title: "500+",
    description: "Resources Shared"
  },
  {
    icon: <Award className="w-8 h-8 text-[#FFD600]" />,
    title: "100+",
    description: "Success Stories"
  }
];

const aiCategories = [
  {
    title: "Mental Wellness",
    description: "Talk about stress, anxiety, or motivation. Get self-care tips and support.",
    icon: <Heart className="w-6 h-6 text-[#FFD600]" />
  },
  {
    title: "Study Help", 
    description: "Ask for study tips, explanations, or help with assignments and concepts.",
    icon: <BookOpen className="w-6 h-6 text-[#FFD600]" />
  },
  {
    title: "Goal Motivation",
    description: "Get encouragement, productivity hacks, and help with time management.",
    icon: <Target className="w-6 h-6 text-[#FFD600]" />
  },
  {
    title: "Personal Support",
    description: "Share your thoughts or worries. Veronica listens without judgment.",
    icon: <Users className="w-6 h-6 text-[#FFD600]" />
  },
  {
    title: "Self-Care Tips",
    description: "Get actionable advice for breaks, mindfulness, and healthy habits.",
    icon: <Coffee className="w-6 h-6 text-[#FFD600]" />
  },
  {
    title: "Productivity Hacks",
    description: "Learn Pomodoro, time management, and focus techniques.",
    icon: <Lightbulb className="w-6 h-6 text-[#FFD600]" />
  }
];

const coreFeatures = [
  {
    title: "Previous Year Questions",
    description: "Get access to carefully curated PYQs to ace your exams with flying colors.",
    icon: <FileText className="w-6 h-6" />,
    count: "500+"
  },
  {
    title: "Notes from Toppers",
    description: "Learn from the best - get access to comprehensive notes from top-performing seniors.",
    icon: <BookOpen className="w-6 h-6" />,
    count: "200+"
  },
  {
    title: "Resume Tools",
    description: "Build ATS-friendly resumes with expert guidance and industry-specific templates.",
    icon: <FileText className="w-6 h-6" />,
    count: "24/7"
  },
  {
    title: "Mentorship",
    description: "Connect directly with seniors who have walked the path before you.",
    icon: <Users className="w-6 h-6" />,
    count: "1:1"
  },
  {
    title: "Real Projects",
    description: "Gain practical experience through industry-relevant projects that boost your portfolio.",
    icon: <Target className="w-6 h-6" />,
    count: "Live"
  },
  {
    title: "Q&A Forum",
    description: "Get your doubts cleared by a community of helpful seniors and peers.",
    icon: <MessageCircle className="w-6 h-6" />,
    count: "24/7"
  }
];

const howItWorksSteps = [
  {
    number: "01",
    title: "Browse",
    description: "Explore our vast library of resources, projects, and mentorship opportunities."
  },
  {
    number: "02", 
    title: "Connect",
    description: "Reach out to seniors for guidance, join study groups, and participate in discussions."
  },
  {
    number: "03",
    title: "Grow", 
    description: "Apply what you've learned, build your portfolio, and become the best version of yourself."
  }
];

const popularResources = [
  {
    title: "Data Structures & Algorithms",
    description: "Master the fundamentals of DSA with comprehensive notes and practice problems.",
    rating: "4.9",
    students: "120 students",
    badge: "TRENDING",
    badgeColor: "bg-red-500",
    link: "/dsa"
  },
  {
    title: "Interview Preparation", 
    description: "Comprehensive guide to technical and HR interviews with real questions.",
    rating: "4.8",
    students: "85 students", 
    badge: "POPULAR",
    badgeColor: "bg-blue-500",
    link: "#"
  },
  {
    title: "Web Development Basics",
    description: "Learn HTML, CSS, and JavaScript from scratch with hands-on projects.",
    rating: "4.7",
    students: "64 students",
    badge: "NEW", 
    badgeColor: "bg-green-500",
    link: "/web-dev-basics"
  }
];

const testimonials = [
  {
    name: "Rahul Sharma",
    course: "Computer Science, 3rd Year",
    text: "The PYQs and notes from seniors helped me top my exams. The mentorship I received was invaluable for my internship hunt.",
    rating: 5
  },
  {
    name: "Priya Patel", 
    course: "Electronics, Final Year",
    text: "The resume tools helped me craft a perfect CV that landed me interviews at my dream companies. Forever grateful to VidyaSathi!",
    rating: 5
  },
  {
    name: "Amit Verma",
    course: "Mechanical, 2nd Year", 
    text: "The community here is amazing! I found study partners, mentors, and friends who helped me navigate through tough academic times.",
    rating: 4
  }
];

export default function Index() {
  const { user } = useAuth();
  const navigate = useNavigate();
  const { data: videoResources } = useVideoResources();

  // Animation refs
  const [heroRef, heroVisible] = useScrollAnimation(0.2);
  const [chaosRef, chaosVisible] = useScrollAnimation(0.3);
  const [featuresRef, featuresVisible] = useScrollAnimation(0.2);
  const [aiRef, aiVisible] = useScrollAnimation(0.2);
  const [howItWorksRef, howItWorksVisible] = useScrollAnimation(0.2);
  const [resourcesRef, resourcesVisible] = useScrollAnimation(0.2);
  const [testimonialsRef, testimonialsVisible] = useScrollAnimation(0.2);
  const [ctaRef, ctaVisible] = useScrollAnimation(0.2);

  console.log("Index page rendering, user:", user);

  const handleGetStarted = () => {
    if (user) {
      navigate('/dashboard');
    } else {
      navigate('/auth');
    }
  };

  const handleViewAllResources = () => {
    if (user) {
      navigate('/dashboard');
    } else {
      navigate('/auth');
    }
  };

  const handleLearnMore = () => {
    const featuresSection = document.getElementById('features');
    if (featuresSection) {
      featuresSection.scrollIntoView({ behavior: 'smooth' });
    }
  };

  const handleResourceClick = (resource) => {
    if (resource.link === "#") {
      // Find interview preparation video from database
      const interviewVideo = videoResources?.find(video => video.category === 'Interview');
      if (interviewVideo) {
        window.open(interviewVideo.video_url, '_blank');
      } else {
        if (user) {
          navigate('/dashboard');
        } else {
          navigate('/auth');
        }
      }
    } else {
      navigate(resource.link);
    }
  };

  return (
    <div className="min-h-screen bg-[#111111] font-sans">
      <Navigation />

      {/* Hero Section */}
      <section ref={heroRef} className="py-20 px-4">
        <div className={`max-w-4xl mx-auto text-center transition-all duration-1000 ${
          heroVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
        }`}>
          <h1 className="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
            Be the <span className="text-[#FFD600]">Senior</span> You Always<br />
            Wanted to Have
          </h1>
          <p className="text-xl md:text-2xl text-gray-300 mb-8 max-w-3xl mx-auto leading-relaxed">
            Access real-world learning resources, mentorship, and career-boosting<br />
            projects, all in one place.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
            <Button 
              size="lg" 
              className="bg-[#FFD600] text-[#111] font-bold text-lg px-8 py-4 min-w-48 hover:bg-[#FFD600]/90 transition-all duration-300 hover:scale-105"
              onClick={handleGetStarted}
            >
              {user ? 'Continue Learning' : 'Join Now'}
            </Button>
            <Button 
              variant="outline" 
              size="lg" 
              className="border-[#FFD600] text-[#FFD600] font-bold text-lg px-8 py-4 min-w-48 transition-all duration-300 hover:scale-105"
              onClick={handleLearnMore}
            >
              Learn More
            </Button>
          </div>
          
          {/* Stats Row */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-2xl mx-auto">
            {features.map((feature, index) => (
              <div 
                key={index} 
                className={`text-center transition-all duration-700 ${
                  heroVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
                }`}
                style={{ transitionDelay: `${index * 200}ms` }}
              >
                <div className="flex justify-center mb-3">
                  {feature.icon}
                </div>
                <h3 className="text-white font-semibold mb-2">{feature.title}</h3>
                <p className="text-gray-400 text-sm">{feature.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Engineering Chaos Section */}
      <section ref={chaosRef} className="py-16 px-4">
        <div className={`max-w-4xl mx-auto text-center transition-all duration-1000 ${
          chaosVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
        }`}>
          <h2 className="text-4xl md:text-5xl font-bold text-white mb-6">
            Tired of being lost in engineering chaos?
          </h2>
          <p className="text-xl text-gray-300">
            VidyaSathi is your senior-powered solution — navigate your engineering journey with confidence!
          </p>
        </div>
      </section>

      {/* Our Core Features Section */}
      <section id="features" ref={featuresRef} className="py-20 px-4">
        <div className="max-w-6xl mx-auto">
          <div className={`text-center mb-16 transition-all duration-1000 ${
            featuresVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
          }`}>
            <h2 className="text-4xl font-bold text-white mb-4">
              Our Core Features
            </h2>
            <p className="text-xl text-gray-400">
              Everything you need to excel in your academic journey
            </p>
          </div>
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {coreFeatures.map((feature, index) => (
              <div 
                key={index} 
                className={`bg-[#1e1e2f] p-6 rounded-xl border border-[#333] hover:border-[#FFD600]/50 transition-all duration-500 hover:transform hover:scale-105 ${
                  featuresVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
                }`}
                style={{ transitionDelay: `${index * 100}ms` }}
              >
                <div className="flex items-center justify-between mb-4">
                  <div className="flex items-center">
                    <div className="p-3 bg-[#FFD600]/10 rounded-lg mr-4 text-[#FFD600]">
                      {feature.icon}
                    </div>
                    <h3 className="text-lg font-semibold text-white">{feature.title}</h3>
                  </div>
                  <span className="text-[#FFD600] font-bold text-lg">{feature.count}</span>
                </div>
                <p className="text-gray-400 leading-relaxed">{feature.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* AI Assistant Categories Section */}
      <section ref={aiRef} className="py-20 px-4 bg-[#0a0a0a]">
        <div className="max-w-6xl mx-auto">
          <div className={`text-center mb-16 transition-all duration-1000 ${
            aiVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
          }`}>
            <h2 className="text-4xl font-bold text-white">
              Veronica <span className="text-[#FFD600]">AI Assistant</span>
            </h2>
          </div>
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {aiCategories.map((category, index) => (
              <div 
                key={index} 
                className={`bg-[#1e1e2f] p-6 rounded-xl border border-[#333] hover:border-[#FFD600]/50 transition-all duration-500 hover:transform hover:scale-105 ${
                  aiVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
                }`}
                style={{ transitionDelay: `${index * 100}ms` }}
              >
                <div className="flex items-center mb-4">
                  <div className="p-3 bg-[#FFD600]/10 rounded-lg mr-4">
                    {category.icon}
                  </div>
                  <h3 className="text-lg font-semibold text-white">{category.title}</h3>
                </div>
                <p className="text-gray-400 leading-relaxed">{category.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* How It Works Section */}
      <section ref={howItWorksRef} className="py-20 px-4">
        <div className="max-w-6xl mx-auto">
          <div className={`text-center mb-16 transition-all duration-1000 ${
            howItWorksVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
          }`}>
            <h2 className="text-4xl font-bold text-white mb-4">
              How It Works
            </h2>
            <p className="text-xl text-gray-400">
              Get started in three simple steps
            </p>
          </div>
          <div className="grid md:grid-cols-3 gap-8">
            {howItWorksSteps.map((step, index) => (
              <div 
                key={index} 
                className={`bg-[#1e1e2f] p-8 rounded-xl border border-[#333] text-center transition-all duration-700 hover:transform hover:scale-105 ${
                  howItWorksVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
                }`}
                style={{ transitionDelay: `${index * 200}ms` }}
              >
                <div className="text-6xl font-bold text-[#FFD600] mb-4">{step.number}</div>
                <h3 className="text-2xl font-bold text-white mb-4">{step.title}</h3>
                <p className="text-gray-400 leading-relaxed">{step.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Popular Resources Section */}
      <section id="resources" ref={resourcesRef} className="py-20 px-4 bg-[#0a0a0a]">
        <div className="max-w-6xl mx-auto">
          <div className={`text-center mb-16 transition-all duration-1000 ${
            resourcesVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
          }`}>
            <h2 className="text-4xl font-bold text-white mb-4">
              Popular Resources
            </h2>
            <p className="text-xl text-gray-400">
              Dive into our most sought-after learning materials
            </p>
          </div>
          <div className="grid md:grid-cols-3 gap-6 mb-8">
            {popularResources.map((resource, index) => (
              <div 
                key={index} 
                className={`bg-[#1e1e2f] p-6 rounded-xl border border-[#333] hover:border-[#FFD600]/50 transition-all duration-500 flex flex-col h-full hover:transform hover:scale-105 ${
                  resourcesVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
                }`}
                style={{ transitionDelay: `${index * 150}ms` }}
              >
                <div className="flex justify-between items-start mb-4">
                  <span className={`px-2 py-1 text-xs font-bold text-white rounded ${resource.badgeColor}`}>
                    {resource.badge}
                  </span>
                  <span className="text-white font-bold">FREE</span>
                </div>
                <h3 className="text-xl font-bold text-white mb-3">{resource.title}</h3>
                <div className="flex items-center mb-3">
                  <Star className="w-4 h-4 text-[#FFD600] fill-current mr-1" />
                  <span className="text-white font-semibold mr-2">{resource.rating}</span>
                  <span className="text-gray-400">★ {resource.students}</span>
                </div>
                <p className="text-gray-400 mb-6 leading-relaxed flex-grow">{resource.description}</p>
                <Button 
                  className="w-full bg-[#FFD600] text-[#111] hover:bg-[#FFD600]/90 mt-auto transition-all duration-300 hover:scale-105"
                  onClick={() => handleResourceClick(resource)}
                >
                  View Resource
                </Button>
              </div>
            ))}
          </div>
          <div className={`text-center transition-all duration-1000 ${
            resourcesVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
          }`}>
            <Button 
              variant="outline" 
              size="lg" 
              className="border-[#FFD600] text-[#FFD600] transition-all duration-300 hover:scale-105"
              onClick={handleViewAllResources}
            >
              View All Resources
            </Button>
          </div>
        </div>
      </section>

      {/* What Students Say Section */}
      <section ref={testimonialsRef} className="py-20 px-4">
        <div className="max-w-6xl mx-auto">
          <div className={`text-center mb-16 transition-all duration-1000 ${
            testimonialsVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
          }`}>
            <h2 className="text-4xl font-bold text-white mb-4">
              What Students Say
            </h2>
            <p className="text-xl text-gray-400">
              Hear from those who've transformed their academic journey
            </p>
          </div>
          <div className="grid md:grid-cols-3 gap-8">
            {testimonials.map((testimonial, index) => (
              <div 
                key={index} 
                className={`bg-[#1e1e2f] p-6 rounded-xl border border-[#333] transition-all duration-700 hover:transform hover:scale-105 ${
                  testimonialsVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
                }`}
                style={{ transitionDelay: `${index * 200}ms` }}
              >
                <div className="mb-4">
                  <h4 className="text-lg font-bold text-white">{testimonial.name}</h4>
                  <p className="text-gray-400 text-sm">{testimonial.course}</p>
                </div>
                <p className="text-gray-300 mb-4 leading-relaxed">"{testimonial.text}"</p>
                <div className="flex">
                  {[...Array(5)].map((_, i) => (
                    <Star 
                      key={i} 
                      className={`w-4 h-4 ${i < testimonial.rating ? 'text-[#FFD600] fill-current' : 'text-gray-600'}`} 
                    />
                  ))}
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Call to Action */}
      <section ref={ctaRef} className="py-20 px-4 bg-[#0a0a0a]">
        <div className={`max-w-4xl mx-auto text-center transition-all duration-1000 ${
          ctaVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
        }`}>
          <h2 className="text-4xl font-bold text-white mb-6">
            Ready to <span className="text-[#FFD600]">Get Started?</span>
          </h2>
          <p className="text-xl text-gray-300 mb-8">
            Join VidyaSathi today and become a part of a growing community of learners and mentors.
          </p>
          <Button 
            size="lg" 
            className="bg-[#FFD600] text-[#111] font-bold text-lg px-8 py-4 transition-all duration-300 hover:scale-105"
            onClick={handleGetStarted}
          >
            {user ? 'Continue Learning' : 'Join VidyaSathi Today'}
          </Button>
        </div>
      </section>

      <Footer />
    </div>
  );
}
