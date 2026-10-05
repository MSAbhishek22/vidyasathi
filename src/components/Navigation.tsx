
import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Menu, X, Home, Rocket, BookOpen, Users, Bot, LogIn, LogOut, User } from "lucide-react";
import { useAuth } from "../hooks/useAuth";
import { useNavigate, useLocation } from "react-router-dom";

interface NavigationLink {
  label: string;
  Icon: any;
  path: string;
  action?: string;
}

const links: NavigationLink[] = [
  { label: "Home", Icon: Home, path: "/" },
  { label: "Features", Icon: Rocket, path: "/#features" },
  { label: "Resources", Icon: BookOpen, path: "/resources" },
  { label: "Community", Icon: Users, path: "/community" },
  { label: "AI Assistant", Icon: Bot, path: "/veronica" },
];

const authenticatedLinks: NavigationLink[] = [
  { label: "Profile", Icon: User, path: "/profile" },
];

export const Navigation = () => {
  const [mobileMenu, setMobileMenu] = useState(false);
  const { user, userRole, signOut } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();

  // Debug logging
  console.log('Navigation - User:', user);
  console.log('Navigation - UserRole:', userRole);

  const handleLogout = async () => {
    const { error } = await signOut();
    if (!error) {
      navigate('/');
    }
  };

  const handleLogin = () => {
    navigate('/auth');
  };

  const handleLinkClick = (link: NavigationLink) => {
    if (link.path === '/') {
      // Handle home navigation - navigate to home page and scroll to top
      navigate('/');
      window.scrollTo({ top: 0, behavior: 'smooth' });
    } else if (link.path.startsWith('/#')) {
      // Handle anchor links - navigate to home first if not already there, then scroll to section
      const sectionId = link.path.substring(2); // Remove '/#'
      
      if (location.pathname !== '/') {
        // Navigate to home page first, then scroll to section
        navigate('/');
        setTimeout(() => {
          const element = document.getElementById(sectionId);
          if (element) {
            element.scrollIntoView({ behavior: 'smooth' });
          }
        }, 100);
      } else {
        // Already on home page, just scroll to section
        const element = document.getElementById(sectionId);
        if (element) {
          element.scrollIntoView({ behavior: 'smooth' });
        }
      }
    } else {
      // Handle regular navigation
      navigate(link.path);
    }
  };

  const navigationLinks = user ? [...links, ...authenticatedLinks] : links;

  return (
    <nav className="bg-[#111]/95 backdrop-blur-sm border-b border-[#222] sticky top-0 z-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Logo - Fixed positioning */}
          <div className="flex-shrink-0">
            <span 
              className="text-2xl font-bold text-[--primary] select-none cursor-pointer"
              onClick={() => navigate('/')}
            >
              VidyaSathi
            </span>
          </div>

          {/* Desktop Navigation */}
          <div className="hidden md:flex items-center space-x-1 flex-1 justify-center">
            {navigationLinks.map((link) => (
              <button
                onClick={() => handleLinkClick(link)}
                key={link.label}
                className="flex items-center px-4 py-2 text-gray-300 hover:text-[--primary] transition-colors rounded-lg hover:bg-[#1a1a1a]"
              >
                <link.Icon className="w-4 h-4 mr-2" />
                {link.label}
              </button>
            ))}
          </div>

          {/* Auth Section - Fixed positioning */}
          <div className="hidden md:flex items-center space-x-3 flex-shrink-0">
            {user ? (
              <div className="flex items-center space-x-3">
                <span className="text-gray-300 text-sm">
                  Welcome, {userRole || 'User'}
                </span>
                <Button variant="ghost" className="text-gray-300 hover:text-[--primary]" onClick={handleLogout}>
                  <LogOut className="w-4 h-4 mr-2" />
                  Logout
                </Button>
              </div>
            ) : (
              <Button variant="ghost" className="text-gray-300 hover:text-[--primary]" onClick={handleLogin}>
                <LogIn className="w-4 h-4 mr-2" />
                Login
              </Button>
            )}
          </div>

          {/* Mobile Menu Button */}
          <button 
            className="md:hidden p-2 text-gray-300 hover:text-[--primary]" 
            onClick={() => setMobileMenu(!mobileMenu)}
          >
            {mobileMenu ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
          </button>
        </div>
      </div>

      {/* Mobile Menu */}
      {mobileMenu && (
        <div className="md:hidden border-t border-[#222] bg-[#111] px-4 py-4 space-y-2">
          {navigationLinks.map((link) => (
            <button
              onClick={() => {
                handleLinkClick(link);
                setMobileMenu(false);
              }}
              key={link.label}
              className="flex items-center px-4 py-3 text-gray-300 hover:text-[--primary] transition-colors rounded-lg hover:bg-[#1a1a1a] w-full"
            >
              <link.Icon className="w-4 h-4 mr-3" />
              {link.label}
            </button>
          ))}
          <div className="pt-2 border-t border-[#222]">
            {user ? (
              <button
                onClick={() => {
                  handleLogout();
                  setMobileMenu(false);
                }}
                className="flex items-center px-4 py-3 text-gray-300 hover:text-[--primary] transition-colors rounded-lg hover:bg-[#1a1a1a] w-full"
              >
                <LogOut className="w-4 h-4 mr-3" />
                Logout
              </button>
            ) : (
              <button
                onClick={() => {
                  handleLogin();
                  setMobileMenu(false);
                }}
                className="flex items-center px-4 py-3 text-gray-300 hover:text-[--primary] transition-colors rounded-lg hover:bg-[#1a1a1a] w-full"
              >
                <LogIn className="w-4 h-4 mr-3" />
                Login
              </button>
            )}
          </div>
        </div>
      )}
    </nav>
  );
};
