//
//  CustomTextField.swift
//  dreamkas
//
//  Created by sig on 02.12.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

import UIKit
import QuartzCore
import Foundation

@objc class CustomTextField: MKTextField {
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
        self.floatingLabelFont = UIFont (name: DefaultFontName, size: 12)!
        self.layer.borderColor = UIColor.clearColor().CGColor
        self.floatingPlaceholderEnabled = true
        self.tintColor = UIColor.MKColor.Blue
        self.rippleLocation = .TapLocation
        self.cornerRadius = 0
        self.bottomBorderEnabled = true
        
        // self.placeholder = ""
    }
}