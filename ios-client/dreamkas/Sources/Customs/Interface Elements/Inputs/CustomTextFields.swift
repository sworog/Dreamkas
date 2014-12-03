//
//  CustomTextFields.swift
//  dreamkas
//
//  Created by sig on 03.12.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

import UIKit
import QuartzCore
import Foundation

@objc class DynamicTextField: MKTextField {
    override init(frame: CGRect) {
        super.init(frame: frame)
        self.initialize()
    }
    
    required init(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        self.initialize()
    }
    
    func initialize() {
        self.font = UIFont (name: DefaultFontName, size: 16)
        self.layer.borderColor = UIColor.clearColor().CGColor
        
        self.floatingLabelFont = UIFont (name: DefaultFontName, size: 12)!
        self.floatingPlaceholderEnabled = true
        
        self.textColor = UIColor.Default.Black
        self.tintColor = UIColor.Default.LightBlue
        self.floatingLabelTextColor = UIColor.Default.Gray
        
        self.rippleLocation = .TapLocation
        self.cornerRadius = CGFloat(DefaultCornerRadius)
        self.bottomBorderEnabled = true
    }
    
    func setAttributedPlaceholder(text : NSString) {
        var attrs = [NSFontAttributeName : self.font, NSForegroundColorAttributeName : UIColor.Default.Gray]
        var str = NSAttributedString(string: text, attributes: attrs)
        
        self.attributedPlaceholder = str
    }
}

@objc class StaticTextField: MKTextField {
    override init(frame: CGRect) {
        super.init(frame: frame)
        self.initialize()
    }
    
    required init(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        self.initialize()
    }
    
    func initialize() {
        self.font = UIFont (name: DefaultFontName, size: 16)
        self.layer.borderColor = UIColor.clearColor().CGColor
        
        self.floatingLabelFont = UIFont (name: DefaultFontName, size: 1)!
        self.floatingPlaceholderEnabled = true
        
        self.textColor = UIColor.Default.Black
        self.tintColor = UIColor.Default.LightBlue
        self.floatingLabelTextColor = UIColor.clearColor()
        
        self.rippleLocation = .TapLocation
        self.cornerRadius = CGFloat(DefaultCornerRadius)
        self.bottomBorderEnabled = true
    }
}