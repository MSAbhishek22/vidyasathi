
import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { AuthProvider, useAuth } from "./hooks/useAuth";
import Index from "./pages/Index";
import Auth from "./pages/Auth";
import StudentDashboard from "./pages/StudentDashboard";
import SeniorDashboard from "./pages/SeniorDashboard";
import AdminDashboard from "./pages/AdminDashboard";
import VeronicaPage from "./pages/VeronicaPage";
import ProfilePage from "./pages/ProfilePage";
import CommunityPage from "./pages/CommunityPage";
import DSAPage from "./pages/DSAPage";
import WebDevBasicsPage from "./pages/WebDevBasicsPage";
import PrivacyPolicy from "./pages/PrivacyPolicy";
import TermsOfService from "./pages/TermsOfService";
import CookiePolicy from "./pages/CookiePolicy";
import NotFound from "./pages/NotFound";
import { Veronica } from "./components/Veronica";

const queryClient = new QueryClient();

// Protected Route Component
const ProtectedRoute = ({ children, allowedRoles }: { children: React.ReactNode; allowedRoles?: string[] }) => {
  const { user, userRole, loading } = useAuth();

  if (loading) {
    return <div className="min-h-screen bg-[#111] flex items-center justify-center">
      <div className="text-[--primary] text-xl">Loading...</div>
    </div>;
  }

  if (!user) {
    return <Navigate to="/auth" replace />;
  }

  if (allowedRoles && !allowedRoles.includes(userRole)) {
    return <Navigate to="/dashboard" replace />;
  }

  return <>{children}</>;
};

// Dashboard Router Component
const DashboardRouter = () => {
  const { userRole } = useAuth();

  switch (userRole) {
    case 'admin':
      return <AdminDashboard />;
    case 'senior':
      return <SeniorDashboard />;
    case 'student':
    default:
      return <StudentDashboard />;
  }
};

const AppRoutes = () => {
  const { user } = useAuth();

  return (
    <>
      <Routes>
        <Route path="/" element={<Index />} />
        <Route 
          path="/auth" 
          element={user ? <Navigate to="/dashboard" replace /> : <Auth />} 
        />
        <Route path="/veronica" element={<VeronicaPage />} />
        <Route path="/community" element={<CommunityPage />} />
        <Route path="/dsa" element={<DSAPage />} />
        <Route path="/web-dev-basics" element={<WebDevBasicsPage />} />
        <Route path="/privacy-policy" element={<PrivacyPolicy />} />
        <Route path="/terms-of-service" element={<TermsOfService />} />
        <Route path="/cookie-policy" element={<CookiePolicy />} />
        <Route 
          path="/profile" 
          element={
            <ProtectedRoute>
              <ProfilePage />
            </ProtectedRoute>
          } 
        />
        <Route 
          path="/dashboard" 
          element={
            <ProtectedRoute>
              <DashboardRouter />
            </ProtectedRoute>
          } 
        />
        <Route 
          path="/resources" 
          element={
            <ProtectedRoute>
              <StudentDashboard />
            </ProtectedRoute>
          } 
        />
        <Route 
          path="/student-dashboard" 
          element={
            <ProtectedRoute allowedRoles={['student']}>
              <StudentDashboard />
            </ProtectedRoute>
          } 
        />
        <Route 
          path="/senior-dashboard" 
          element={
            <ProtectedRoute allowedRoles={['senior']}>
              <SeniorDashboard />
            </ProtectedRoute>
          } 
        />
        <Route 
          path="/admin-dashboard" 
          element={
            <ProtectedRoute allowedRoles={['admin']}>
              <AdminDashboard />
            </ProtectedRoute>
          } 
        />
        <Route path="*" element={<NotFound />} />
      </Routes>
      <Veronica />
    </>
  );
};

const App = () => (
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <Toaster />
      <Sonner />
      <BrowserRouter>
        <AuthProvider>
          <AppRoutes />
        </AuthProvider>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
