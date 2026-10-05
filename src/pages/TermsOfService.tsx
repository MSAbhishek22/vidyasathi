
import { Navigation } from "@/components/Navigation";
import { Footer } from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { ArrowLeft } from "lucide-react";
import { useNavigate } from "react-router-dom";

export default function TermsOfService() {
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
          <h1 className="text-5xl font-bold text-white mb-4">Terms of Service</h1>
          <p className="text-gray-400 text-lg">Last updated: January 2024</p>
        </div>
        
        {/* Terms of Service Content */}
        <div className="max-w-4xl mx-auto">
          <div className="bg-[#1a1a1a] border border-[#333] rounded-lg p-8 space-y-8">
            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">1. Acceptance of Terms</h2>
              <p className="text-gray-300 mb-4">
                By accessing and using VidyaSathi, you accept and agree to be bound by the terms and provision of this agreement.
              </p>
            </section>

            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">2. Use License</h2>
              <p className="text-gray-300 mb-4">
                Permission is granted to temporarily access VidyaSathi for personal, non-commercial transitory viewing only.
              </p>
              <ul className="list-disc list-inside text-gray-300 space-y-2">
                <li>Modify or copy the materials</li>
                <li>Use the materials for any commercial purpose</li>
                <li>Attempt to reverse engineer any software</li>
                <li>Remove any copyright or other proprietary notations</li>
              </ul>
            </section>

            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">3. User Responsibilities</h2>
              <p className="text-gray-300 mb-4">
                As a user of VidyaSathi, you agree to:
              </p>
              <ul className="list-disc list-inside text-gray-300 space-y-2">
                <li>Provide accurate and complete information</li>
                <li>Maintain the security of your account</li>
                <li>Respect other users and their privacy</li>
                <li>Not engage in any harmful or illegal activities</li>
              </ul>
            </section>

            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">4. Limitations</h2>
              <p className="text-gray-300 mb-4">
                In no event shall VidyaSathi or its suppliers be liable for any damages arising out of the use or inability to use the materials on VidyaSathi's website.
              </p>
            </section>

            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">5. Contact Information</h2>
              <p className="text-gray-300">
                If you have any questions about these Terms of Service, please contact us at legal@vidyasathi.com
              </p>
            </section>
          </div>
        </div>
      </div>
      
      <Footer />
    </div>
  );
}
