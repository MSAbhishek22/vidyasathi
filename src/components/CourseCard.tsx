
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardFooter, CardHeader } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Star, Clock, Users, BookOpen } from "lucide-react";

interface Course {
  id: number;
  title: string;
  description: string;
  instructor: string;
  rating: number;
  students: number;
  price: number;
  originalPrice: number;
  image: string;
  duration: string;
  level: string;
}

interface CourseCardProps {
  course: Course;
}

export const CourseCard = ({ course }: CourseCardProps) => {
  const discount = Math.round(((course.originalPrice - course.price) / course.originalPrice) * 100);

  return (
    <Card className="group hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border-gray-200 overflow-hidden">
      <CardHeader className="p-0">
        <div className="relative">
          <img 
            src={course.image} 
            alt={course.title}
            className="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
          />
          <div className="absolute top-3 left-3">
            <Badge className="bg-blue-600 text-white hover:bg-blue-700">
              {discount}% OFF
            </Badge>
          </div>
          <div className="absolute top-3 right-3">
            <Badge variant="secondary" className="bg-white/90 text-gray-700">
              {course.level}
            </Badge>
          </div>
        </div>
      </CardHeader>
      
      <CardContent className="p-6 space-y-4">
        <div>
          <h3 className="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
            {course.title}
          </h3>
          <p className="text-sm text-gray-600 line-clamp-3">
            {course.description}
          </p>
        </div>
        
        <div className="text-sm text-gray-600">
          <span>by </span>
          <span className="font-medium text-blue-600">{course.instructor}</span>
        </div>
        
        <div className="flex items-center justify-between text-sm text-gray-600">
          <div className="flex items-center space-x-1">
            <Star className="h-4 w-4 text-yellow-500 fill-current" />
            <span className="font-medium">{course.rating}</span>
          </div>
          <div className="flex items-center space-x-1">
            <Users className="h-4 w-4" />
            <span>{course.students.toLocaleString()}</span>
          </div>
          <div className="flex items-center space-x-1">
            <Clock className="h-4 w-4" />
            <span>{course.duration}</span>
          </div>
        </div>
      </CardContent>
      
      <CardFooter className="p-6 pt-0 flex items-center justify-between">
        <div className="space-y-1">
          <div className="flex items-center space-x-2">
            <span className="text-xl font-bold text-gray-900">₹{course.price}</span>
            <span className="text-sm text-gray-500 line-through">₹{course.originalPrice}</span>
          </div>
        </div>
        <Button className="bg-blue-600 hover:bg-blue-700">
          Enroll Now
        </Button>
      </CardFooter>
    </Card>
  );
};
