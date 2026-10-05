
-- Add a new table for video resources
CREATE TABLE public.video_resources (
  id UUID NOT NULL DEFAULT gen_random_uuid() PRIMARY KEY,
  title TEXT NOT NULL,
  description TEXT,
  video_url TEXT NOT NULL,
  category TEXT NOT NULL,
  created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT now(),
  updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT now()
);

-- Add Row Level Security (RLS) to make video resources publicly readable
ALTER TABLE public.video_resources ENABLE ROW LEVEL SECURITY;

-- Create policy that allows everyone to read video resources
CREATE POLICY "Anyone can view video resources" 
  ON public.video_resources 
  FOR SELECT 
  USING (true);

-- Create policy that only authenticated users can insert video resources (for admin purposes)
CREATE POLICY "Authenticated users can create video resources" 
  ON public.video_resources 
  FOR INSERT 
  WITH CHECK (auth.role() = 'authenticated');

-- Insert the DSA YouTube video
INSERT INTO public.video_resources (title, description, video_url, category)
VALUES (
  'Data Structures & Algorithms - Complete Course',
  'Comprehensive DSA video tutorial covering all essential concepts with practical examples and coding implementations.',
  'https://www.youtube.com/watch?v=VTLCoHnyACE&list=PLfqMhTWNBTe137I_EPQd34TsgV6IO55pt',
  'DSA'
);
