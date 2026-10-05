
import * as React from "react"
import { Slot } from "@radix-ui/react-slot"
import { cva, type VariantProps } from "class-variance-authority"
import { cn } from "@/lib/utils"

const buttonVariants = cva(
  "inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md transition-all duration-200 text-base font-semibold px-6 py-3 shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2",
  {
    variants: {
      variant: {
        default: "bg-[#FFD600] text-[#111] hover:bg-[#FFD600]/90 focus:ring-[#FFD600]",
        outline: "border-2 border-[#FFD600] bg-transparent text-[#FFD600] hover:bg-[#FFD600]/10 focus:ring-[#FFD600]",
        ghost: "bg-transparent text-[#FFD600] hover:bg-[#FFD600]/5 focus:ring-[#FFD600]",
        link: "underline text-[#FFD600] hover:text-[#FFD600]/80",
        secondary: "bg-[#333] text-white border border-[#444] hover:bg-[#444]",
      },
      size: {
        default: "text-base py-3 px-6",
        sm: "text-sm py-2 px-4",
        lg: "text-lg py-4 px-8",
        icon: "p-2 rounded-full w-12 h-12 flex items-center justify-center",
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  }
)

export interface ButtonProps
  extends React.ButtonHTMLAttributes<HTMLButtonElement>,
    VariantProps<typeof buttonVariants> {
  asChild?: boolean
}

const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
  ({ className, variant, size, asChild = false, ...props }, ref) => {
    const Comp = asChild ? Slot : "button"
    return (
      <Comp
        className={cn(buttonVariants({ variant, size, className }))}
        ref={ref}
        {...props}
      />
    )
  }
)
Button.displayName = "Button"

export { Button, buttonVariants }
