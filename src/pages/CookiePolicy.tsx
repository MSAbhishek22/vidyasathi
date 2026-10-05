
import { Navigation } from "@/components/Navigation";
import { Footer } from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { ArrowLeft } from "lucide-react";
import { useNavigate } from "react-router-dom";

export default function CookiePolicy() {
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
          <h1 className="text-5xl font-bold text-white mb-4">Cookie Policy</h1>
          <p className="text-gray-400 text-lg">Last updated: January 2024</p>
        </div>
        
        {/* Cookie Policy Content */}
        <div className="max-w-4xl mx-auto">
          <div className="bg-[#1a1a1a] border border-[#333] rounded-lg p-8 space-y-8">
            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">1. What Are Cookies</h2>
              <p className="text-gray-300 mb-4">
                Cookies are small text files that are placed on your computer or mobile device when you visit our website. They are widely used to make websites work more efficiently and provide information to website owners.
              </p>
            </section>

            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">2. How We Use Cookies</h2>
              <p className="text-gray-300 mb-4">
                VidyaSathi uses cookies for the following purposes:
              </p>
              <ul className="list-disc list-inside text-gray-300 space-y-2">
                <li>Essential cookies: Required for the website to function properly</li>
                <li>Performance cookies: Help us understand how visitors use our website</li>
                <li>Functionality cookies: Remember your preferences and settings</li>
                <li>Analytics cookies: Help us improve our website and services</li>
              </ul>
            </section>

            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">3. Types of Cookies We Use</h2>
              <div className="space-y-4">
                <div>
                  <h3 className="text-lg font-semibold text-white mb-2">Session Cookies</h3>
                  <p className="text-gray-300">These are temporary cookies that expire when you close your browser.</p>
                </div>
                <div>
                  <h3 className="text-lg font-semibold text-white mb-2">Persistent Cookies</h3>
                  <p className="text-gray-300">These remain on your device for a set period or until you delete them.</p>
                </div>
              </div>
            </section>

            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">4. Managing Cookies</h2>
              <p className="text-gray-300 mb-4">
                You can control and manage cookies in various ways. Most browsers allow you to refuse cookies or delete cookies that have already been set.
              </p>
            </section>

            <section>
              <h2 className="text-2xl font-bold text-[#FFD600] mb-4">5. Contact Us</h2>
              <p className="text-gray-300">
                If you have any questions about our Cookie Policy, please contact us at cookies@vidyasathi.com
              </p>
            </section>
          </div>
        </div>
      </div>
      
      <Footer />
    </div>
  );
}
