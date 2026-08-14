"use client";

import React, { createContext, useContext, useEffect, useState } from "react";

export type ThemeMode = "teal" | "dark" | "light" | "green" | "orange" | "purple";

interface ThemeContextType {
  theme: ThemeMode;
  setTheme: (theme: ThemeMode) => void;
  themeConfig: {
    name: string;
    primary: string;
    primaryBg: string;
    sidebarBg: string;
    badgeBg: string;
  };
}

const THEME_CONFIGS: Record<ThemeMode, { name: string; primary: string; primaryBg: string; sidebarBg: string; badgeBg: string }> = {
  teal: {
    name: "Perfex Classic Teal",
    primary: "#28b8da",
    primaryBg: "from-cyan-500 to-teal-600",
    sidebarBg: "bg-[#25292e]",
    badgeBg: "bg-[#28b8da]/10 text-[#28b8da] border-[#28b8da]/30",
  },
  dark: {
    name: "Midnight Dark",
    primary: "#6366f1",
    primaryBg: "from-indigo-500 to-purple-600",
    sidebarBg: "bg-slate-900/90",
    badgeBg: "bg-indigo-500/10 text-indigo-400 border-indigo-500/30",
  },
  light: {
    name: "Clean Light",
    primary: "#0284c7",
    primaryBg: "from-sky-500 to-blue-600",
    sidebarBg: "bg-slate-900/80",
    badgeBg: "bg-sky-500/10 text-sky-400 border-sky-500/30",
  },
  green: {
    name: "Emerald Green",
    primary: "#10b981",
    primaryBg: "from-emerald-500 to-teal-600",
    sidebarBg: "bg-slate-900/90",
    badgeBg: "bg-emerald-500/10 text-emerald-400 border-emerald-500/30",
  },
  orange: {
    name: "Warm Sunset",
    primary: "#f97316",
    primaryBg: "from-amber-500 to-orange-600",
    sidebarBg: "bg-slate-900/90",
    badgeBg: "bg-orange-500/10 text-orange-400 border-orange-500/30",
  },
  purple: {
    name: "Royal Purple",
    primary: "#8b5cf6",
    primaryBg: "from-purple-500 to-indigo-600",
    sidebarBg: "bg-slate-900/90",
    badgeBg: "bg-purple-500/10 text-purple-400 border-purple-500/30",
  },
};

const ThemeContext = createContext<ThemeContextType | undefined>(undefined);

export function ThemeProvider({ children }: { children: React.ReactNode }) {
  const [theme, setThemeState] = useState<ThemeMode>("teal");

  useEffect(() => {
    const saved = localStorage.getItem("perfex_theme") as ThemeMode;
    if (saved && THEME_CONFIGS[saved]) {
      setThemeState(saved);
    }
  }, []);

  const setTheme = (newTheme: ThemeMode) => {
    setThemeState(newTheme);
    localStorage.setItem("perfex_theme", newTheme);
  };

  return (
    <ThemeContext.Provider
      value={{
        theme,
        setTheme,
        themeConfig: THEME_CONFIGS[theme],
      }}
    >
      <div data-perfex-theme={theme} className="theme-wrapper font-sans min-h-screen">
        {children}
      </div>
    </ThemeContext.Provider>
  );
}

export function usePerfexTheme() {
  const context = useContext(ThemeContext);
  if (!context) {
    throw new Error("usePerfexTheme must be used within ThemeProvider");
  }
  return context;
}
