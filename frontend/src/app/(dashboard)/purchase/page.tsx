"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { MOCK_PURCHASE_ORDERS } from "@/lib/mock-data";
import { useTranslation } from "@/providers/language-provider";
import { ShoppingCart, Plus } from "lucide-react";

export default function PurchasePage() {
  const { t, formatCurrency, formatDate } = useTranslation();

  const { data: pos } = useQuery({
    queryKey: ["purchase-orders"],
    queryFn: async () => {
      try {
        const response = await apiClient.get("/purchase/orders");
        return response.data;
      } catch {
        return MOCK_PURCHASE_ORDERS;
      }
    },
    initialData: MOCK_PURCHASE_ORDERS,
  });

  return (
    <div className="space-y-8 animate-fadeIn">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <ShoppingCart className="h-6 w-6 text-orange-400" />
            {t("purchase.title")}
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            {t("purchase.subtitle")}
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-400 text-slate-950 font-bold text-xs shadow-lg shadow-orange-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> {t("purchase.addNew")}
        </button>
      </div>

      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <table className="w-full text-left text-xs">
          <thead className="bg-slate-950/80 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-800">
            <tr>
              <th className="p-4">{t("purchase.poNumber")}</th>
              <th className="p-4">{t("purchase.vendor")}</th>
              <th className="p-4">{t("purchase.orderDate")}</th>
              <th className="p-4">{t("invoice.dueDate")}</th>
              <th className="p-4">{t("purchase.totalAmount")}</th>
              <th className="p-4 text-right">{t("common.status")}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800/60 text-slate-200">
            {pos.map((po: any) => (
              <tr key={po.poNumber} className="hover:bg-slate-800/40 transition-colors">
                <td className="p-4 font-mono font-bold text-orange-400">{po.poNumber}</td>
                <td className="p-4 font-bold text-white">{po.vendor}</td>
                <td className="p-4 text-slate-400">{formatDate(po.date)}</td>
                <td className="p-4 text-slate-400">{formatDate(po.expectedDelivery)}</td>
                <td className="p-4 font-bold text-orange-400">{formatCurrency(po.totalAmount)}</td>
                <td className="p-4 text-right">
                  <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-500/10 text-orange-400 border border-orange-500/20">
                    {po.status}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
