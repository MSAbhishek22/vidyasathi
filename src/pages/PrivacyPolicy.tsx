
import { Navigation } from "@/components/Navigation";
import { Footer } from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { ArrowLeft } from "lucide-react";
import { useNavigate } from "react-router-dom";

export default function PrivacyPolicy() {
  const navigate = useNavigate();

  return (
    <div className="min-h-screen bg-[#111]">
      <Navigation />
      
      {/* Back Button */}
      <div className="container mx-auto px-6 pt-8">
        <Button
          onClick={() => navigate('/')}
          variant="ghost"
          className="text-white hover:text-[#FFD600] mb-8"
        >
          <ArrowLeft className="w-5 h-5 mr-2" />
          Back to Home
        </Button>
      </div>
      
      <div className="container mx-auto px-6 py-8 mb-20">
        {/* Page Heading */}
        <div className="text-center mb-16">
          <h1 className="text-5xl font-bold text-white mb-4">Privacy Policy</h1>
          <p className="text-gray-400 text-lg">Last updated: January 2024</p>
        </div>
        
        {/* Privacy Policy Content */}
        <div className="max-w-4xl mx-auto">
          <div className="bg-[#1a1a1a] border border-[#333] rounded-lg p-8 space-y-8">
            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">1. Information We Collect</h2>
              <p className="text-gray-300 mb-4">
                We collect information you provide directly to us, such as when you create an account, update your profile, or contact us for support.
              </p>
              <ul className="list-disc list-inside text-gray-300 space-y-2">
                <li>Personal information (name, email address, phone number)</li>
                <li>Educational information (university, course, year of study)</li>
                <li>Usage data and preferences</li>
                <li>Communications between you and other users</li>
              </ul>
            </section>

            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">2. How We Use Your Information</h2>
              <p className="text-gray-300 mb-4">
                We use the information we collect to provide, maintain, and improve our services:
              </p>
              <ul className="list-disc list-inside text-gray-300 space-y-2">
                <li>To provide and maintain VidyaSathi services</li>
                <li>To connect you with relevant seniors and mentors</li>
                <li>To send you updates and educational content</li>
                <li>To improve our platform and user experience</li>
              </ul>
            </section>

            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">3. Information Sharing</h2>
              <p className="text-gray-300 mb-4">
                We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.
              </p>
            </section>

            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">4. Data Security</h2>
              <p className="text-gray-300 mb-4">
                We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.
              </p>
            </section>

            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">5. Contact Us</h2>
              <p className="text-gray-300">
                If you have any questions about this Privacy Policy, please contact us at privacy@vidyasathi.com
              </p>
            </section>
          </div>
        </div>
      </div>
      
      <Footer />
    </div>
  );
}
