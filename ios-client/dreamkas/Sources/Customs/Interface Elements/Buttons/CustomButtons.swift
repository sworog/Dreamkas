//
//  CustomButtons.swift
//  dreamkas
//
//  Created by sig on 03.12.14.
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
        self.enableShadows()
    }
    
    override func setTitle(title: String?, forState state: UIControlState) {
        super.setTitle(title?.uppercaseString, forState: state)
    }
    
    func enableShadows() {
        self.layer.shadowOpacity = 0.24
        self.layer.shadowRadius = 1.0
        self.layer.shadowColor = UIColor.Default.Black.CGColor
        self.layer.shadowOffset = CGSize(width: 0, height: 1.0)
    }
    
    func disableShadows() {
        self.layer.shadowOpacity = 0
        self.layer.shadowRadius = 0
        self.layer.shadowColor = nil
        self.layer.shadowOffset = CGSize(width: 0, height: 0)
    }
}

@objc class RaisedFilledButton: RaisedButton {
    override init(frame: CGRect) {
        super.init(frame: frame)
        self.initialize()
    }
    
    required init(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        self.initialize()
    }
    
    override func initialize() {
        super.initialize()
        
        self.rippleLocation = MKRippleLocation.TapLocation
        self.circleLayerColor = UIColor.Default.Blue
        self.backgroundColor = UIColor.Default.LightBlue
        self.backgroundLayerColor = UIColor.Default.LightBlue
        
        self.setTitleColor(UIColor.Default.White, forState: UIControlState.Normal)
        self.setTitleColor(UIColor.Default.White, forState: UIControlState.Highlighted)
        self.setTitleColor(UIColor.Default.DarkGray, forState: UIControlState.Disabled)
    }
    
    override var enabled: Bool {
        didSet {
            if (enabled) {
                self.backgroundColor = UIColor.Default.LightBlue
                self.enableShadows()
            }
            else {
                self.backgroundColor = UIColor.Default.LightGray
                self.disableShadows()
            }
        }
    }
}

@objc class RaisedEmptyButton: RaisedButton {
    override init(frame: CGRect) {
        super.init(frame: frame)
        self.initialize()
    }
    
    required init(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        self.initialize()
    }
    
    override func initialize() {
        super.initialize()
        
        self.rippleLocation = MKRippleLocation.TapLocation
        self.circleLayerColor = UIColor.Default.Blue
        self.backgroundColor = UIColor.Default.White
        self.backgroundLayerColor = UIColor.Default.LightGray
        
        self.setTitleColor(UIColor.Default.LightBlue, forState: UIControlState.Normal)
        self.setTitleColor(UIColor.Default.LightBlue, forState: UIControlState.Highlighted)
        self.setTitleColor(UIColor.Default.DarkGray, forState: UIControlState.Disabled)
    }
    
    override var enabled: Bool {
        didSet {
            if (enabled) {
                self.backgroundColor = UIColor.Default.White
                self.enableShadows()
            }
            else {
                self.backgroundColor = UIColor.Default.LightGray
                self.disableShadows()
            }
        }
    }
}

@objc class FlatButton: RaisedButton {
    override init(frame: CGRect) {
        super.init(frame: frame)
        self.initialize()
    }
    
    required init(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        self.initialize()
    }
    
    override func initialize() {
        super.initialize()
        
        self.disableShadows()
        
        self.rippleLocation = MKRippleLocation.TapLocation
        self.circleLayerColor = UIColor.Default.Blue
        self.backgroundColor = UIColor.Default.White
        self.backgroundLayerColor = UIColor.Default.LightGray
        
        self.setTitleColor(UIColor.Default.Black, forState: UIControlState.Normal)
        self.setTitleColor(UIColor.Default.Black, forState: UIControlState.Highlighted)
        self.setTitleColor(UIColor.Default.Gray, forState: UIControlState.Disabled)
    }
}
