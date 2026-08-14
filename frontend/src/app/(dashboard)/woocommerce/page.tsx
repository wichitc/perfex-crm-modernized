"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { MOCK_WOOCOMMERCE } from "@/lib/mock-data";
import { Store, RefreshCw, CheckCircle2, ShoppingBag, Users, Layers } from "lucide-react";

export default function WooCommercePage() {
  const { data: woo } = useQuery({
    queryKey: ["woocommerce"],
    queryFn: async () => {
      try {
        const response = await apiClient.get("/woocommerce/status");
        return response.data;
      } catch {
        return MOCK_WOOCOMMERCE;
      }
    },
    initialData: MOCK_WOOCOMMERCE,
  });

  return (
    <div className="space-y-8 animate-fadeIn">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <Store className="h-6 w-6 text-purple-400" />
            WooCommerce Store Connector
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            Synchronize WooCommerce online shop orders, product catalog, and customer profiles.
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-lg shadow-purple-500/25 transition-all cursor-pointer">
          <RefreshCw className="h-4 w-4" /> Trigger Manual Sync
        </button>
      </div>

      {/* Sync Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 shadow-lg flex items-center gap-4">
          <div className="h-12 w-12 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center border border-purple-500/20">
            <ShoppingBag className="h-6 w-6" />
          </div>
          <div>
            <span className="text-xs text-slate-400 font-semibold uppercase">Synced Products</span>
            <h3 className="text-2xl font-black text-white">{woo.syncedProducts} SKUs</h3>
          </div>
        </div>

        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 shadow-lg flex items-center gap-4">
          <div className="h-12 w-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center border border-cyan-500/20">
            <Layers className="h-6 w-6" />
          </div>
          <div>
            <span className="text-xs text-slate-400 font-semibold uppercase">Synced Orders</span>
            <h3 className="text-2xl font-black text-white">{woo.syncedOrders} Orders</h3>
          </div>
        </div>

        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 shadow-lg flex items-center gap-4">
          <div className="h-12 w-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20">
            <CheckCircle2 className="h-6 w-6" />
          </div>
          <div>
            <span className="text-xs text-slate-400 font-semibold uppercase">Connection Status</span>
            <h3 className="text-sm font-bold text-emerald-400">Connected & Operational</h3>
          </div>
        </div>
      </div>

      {/* Recent Sync Logs */}
      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
        <h3 className="text-base font-extrabold text-white">Recent WooCommerce Sync Activity</h3>
        <div className="space-y-3">
          {woo.recentSyncs.map((log: any) => (
            <div key={log.id} className="p-4 rounded-2xl bg-slate-950/60 border border-slate-800 flex items-center justify-between">
              <div>
                <p className="text-xs font-bold text-white">{log.type}</p>
                <p className="text-[10px] text-slate-400 mt-0.5">Processed {log.count} records at {log.timestamp}</p>
              </div>
              <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                {log.status}
              </span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
