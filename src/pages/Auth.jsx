
import { useState } from 'react';
import { Login } from '../components/auth/Login';
import { Register } from '../components/auth/Register';

export default function Auth() {
  const [isLogin, setIsLogin] = useState(true);

  return (
    <div className="min-h-screen bg-[#111] flex items-center justify-center px-4">
      <div className="w-full max-w-md">
        {isLogin ? (
          <Login onToggle={() => setIsLogin(false)} />
        ) : (
          <Register onToggle={() => setIsLogin(true)} />
        )}
      </div>
    </div>
  );
}
