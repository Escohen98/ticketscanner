//
//  ViewController.swift
//  ticket-scanner
//
//  Created by Student User on 1/23/19.
//  Copyright © 2019 Zeta Beta Tau. All rights reserved.
//

import UIKit

class ViewController: UIViewController {
    var code : String = ""
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
    }
    
    @IBAction func updateCode(_ sender: UIButton) {
        switch sender.titleLabel!.text {
        case "<-":
            if(code.count > 0) {
                code = String(code.prefix(code.count-1))
                updateLabel()
            }
            break
        case "Enter":
            if(code.count == 4) {
                fetchData(code);
            }
            break
        default:
            if(code.count < 4) {
                code += String(sender.titleLabel!.text ?? "#")
                updateLabel()
            }
            break
        }
    }
    
    @IBOutlet weak var label: UILabel!
    func updateLabel() {
        label.text = code
    }
    
    func fetchData(_ code: String) {
        
    }
}

