
import { Link } from "react-router-dom";

export const Footer = () => (
  <footer className="bg-[#0a0a0a] border-t border-[#333]">
    <div className="max-w-7xl mx-auto px-4 py-12">
      <div className="grid md:grid-cols-4 gap-8">
        {/* Brand */}
        <div className="md:col-span-1">
          <span className="text-2xl font-bold text-[#FFD600] mb-4 block">VidyaSathi</span>
          <p className="text-gray-400 max-w-md mb-4">
            Be the senior you always wanted to have. Navigate your educational journey with confidence.
          </p>
        </div>

        {/* Resources */}
        <div>
          <h4 className="font-semibold text-white mb-4">Resources</h4>
          <div className="space-y-2">
            <Link to="/resources" className="block text-gray-400 hover:text-[#FFD600] transition-colors">PYQs</Link>
            <Link to="/resources" className="block text-gray-400 hover:text-[#FFD600] transition-colors">Notes</Link>
            <Link to="/resources" className="block text-gray-400 hover:text-[#FFD600] transition-colors">Resume Builder</Link>
            <Link to="/resources" className="block text-gray-400 hover:text-[#FFD600] transition-colors">Q&A Forum</Link>
          </div>
        </div>

        {/* Community */}
        <div>
          <h4 className="font-semibold text-white mb-4">Community</h4>
          <div className="space-y-2">
            <a href="#" className="block text-gray-400 hover:text-[#FFD600] transition-colors">Connect with Seniors</a>
            <a href="#" className="block text-gray-400 hover:text-[#FFD600] transition-colors">Study Groups</a>
            <a href="#" className="block text-gray-400 hover:text-[#FFD600] transition-colors">Mentorship</a>
            <a href="#" className="block text-gray-400 hover:text-[#FFD600] transition-colors">Events</a>
          </div>
        </div>

        {/* Legal */}
        <div>
          <h4 className="font-semibold text-white mb-4">Legal</h4>
          <div className="space-y-2">
            <Link to="/privacy-policy" className="block text-gray-400 hover:text-[#FFD600] transition-colors">Privacy Policy</Link>
            <Link to="/terms-of-service" className="block text-gray-400 hover:text-[#FFD600] transition-colors">Terms of Service</Link>
            <Link to="/cookie-policy" className="block text-gray-400 hover:text-[#FFD600] transition-colors">Cookie Policy</Link>
          </div>
        </div>
      </div>
      
      <div className="border-t border-[#333] mt-8 pt-8 text-center">
        <p className="text-gray-500">© 2024 VidyaSathi. All rights reserved.</p>
      </div>
    </div>
  </footer>
);
