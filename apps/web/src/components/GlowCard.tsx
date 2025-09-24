import React from 'react';

export default function GlowCard({ children, className = '' }: { children: React.ReactNode; className?: string }) {
  return (
    <div className={`relative rounded-2xl p-[1px] bg-gradient-to-br from-cyan-400/30 via-fuchsia-400/20 to-transparent ${className}`}>
      <div className="rounded-2xl card">
        {children}
      </div>
    </div>
  );
}

