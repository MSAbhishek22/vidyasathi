
-- Create a table to store PDF resources
CREATE TABLE public.pdf_resources (
  id UUID NOT NULL DEFAULT gen_random_uuid() PRIMARY KEY,
  title TEXT NOT NULL,
  description TEXT,
  pdf_url TEXT NOT NULL,
  category TEXT NOT NULL,
  created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT now(),
  updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT now()
);

-- Add Row Level Security (RLS) to make PDF resources publicly readable
ALTER TABLE public.pdf_resources ENABLE ROW LEVEL SECURITY;

-- Create policy that allows everyone to read PDF resources
CREATE POLICY "Anyone can view PDF resources" 
  ON public.pdf_resources 
  FOR SELECT 
  USING (true);

-- Create policy that only authenticated users can insert PDF resources (for admin purposes)
CREATE POLICY "Authenticated users can create PDF resources" 
  ON public.pdf_resources 
  FOR INSERT 
  WITH CHECK (auth.role() = 'authenticated');

-- Insert the DSA notes
INSERT INTO public.pdf_resources (title, description, pdf_url, category)
VALUES (
  'Data Structures & Algorithms - Complete Notes',
  'Comprehensive DSA notes covering arrays, linked lists, trees, graphs, algorithms, and more with examples and practice problems.',
  'https://cwh-full-next-space.fra1.cdn.digitaloceanspaces.com/notes/DSA_CompleteNotes.pdf',
  'DSA'
);
