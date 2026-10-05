
import { useQuery } from '@tanstack/react-query';
import { supabase } from '@/integrations/supabase/client';

export const usePdfResources = (category?: string) => {
  return useQuery({
    queryKey: ['pdf-resources', category],
    queryFn: async () => {
      let query = supabase
        .from('pdf_resources')
        .select('*')
        .order('created_at', { ascending: false });

      if (category) {
        query = query.eq('category', category);
      }

      const { data, error } = await query;

      if (error) {
        throw error;
      }

      return data;
    },
  });
};
