//
//  RaisedButton.swift
//  dreamkas
//
//  Created by sig on 02.12.14.
//  Copyright (c) 2014 Dreamkas. All rights reserved.
//

import UIKit
import QuartzCore
import Foundation

@objc class RaisedButton: MKButton {
    override init(frame: CGRect) {
        super.init(frame: frame)
        self.initialize()
    }
    
    required init(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        self.initialize()
    }
    
    func initialize() {
        self.cornerRadius = CGFloat(DefaultCornerRadius)
        
        self.titleLabel!.font = UIFont (name: DefaultMediumFontName, size: 14)
        self .setTitleColor(UIColor.whiteColor(), forState: UIControlState.Normal)
        self .setTitleColor(UIColor.whiteColor(), forState: UIControlState.Highlighted)
        self .setTitleColor(UIColor.grayColor(), forState: UIControlState.Disabled)
        
        self.layer.shadowOpacity = 0.24
        self.layer.shadowRadius = 1.0
        self.layer.shadowColor = UIColor.grayColor().CGColor
        self.layer.shadowOffset = CGSize(width: 0, height: 1.0)
    }
}