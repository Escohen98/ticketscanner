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
    
    //Calls PHP to check if code is active in database. Returns a success boolean to display result.
    //Taken from https://stackoverflow.com/questions/26364914/http-request-in-swift-with-post-method/26365148
    func fetchData(_ code: String) {
        let url = URL(string: "https://afternoon-citadel-95316.herokuapp.com/backend/scanner.php")!
        var request = URLRequest(url: url)
        request.setValue("application/x-www-form-urlencoded", forHTTPHeaderField: "Content-Type")
        request.httpMethod = "POST"
        let postString = "code=\(code)"
        request.httpBody = postString.data(using: .utf8)
        let task = URLSession.shared.dataTask(with: request) { data, response, error in
            guard let data = data, error == nil else {                                                 // check for fundamental networking error
                print("error=\(error)")
                return
            }
            
            if let httpStatus = response as? HTTPURLResponse, httpStatus.statusCode != 200 {           // check for http errors
                print("statusCode should be 200, but is \(httpStatus.statusCode)")
                print("response = \(response)")
            }
            let responseString = String(data: data, encoding: .utf8)!
            self.changeBackground(responseString)
            //Converts String to JSON Array -- Does not work
            /*let newData = responseString.data(using: .utf8)!
            do {
                if let jsonArray = try JSONSerialization.jsonObject(with: newData, options : .allowFragments) as? [Dictionary<String,Bool>]
                {
                    print("Result: \(jsonArray)") // use the json here
                } else {
                    print("bad json")
                }
            } catch let error as NSError {
                print(error)
            }*/
        }
        task.resume()
    }
    
    //Changes the color of the background label depending on the result. 
    func changeBackground(_ result : String) {
        
        DispatchQueue.main.async {
            self.label.backgroundColor = UIColor.red
        if(result.count != 16) {
        //if(result["active"] ?? false) {
            self.label.backgroundColor = UIColor.green
        }
        }
    }
}

