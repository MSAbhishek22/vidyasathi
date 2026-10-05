
import { useQuery } from '@tanstack/react-query';
import { supabase } from '@/integrations/supabase/client';

export const useVideoResources = (category?: string) => {
  return useQuery({
    queryKey: ['video-resources', category],
    queryFn: async () => {
      let query = supabase
        .from('video_resources')
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
