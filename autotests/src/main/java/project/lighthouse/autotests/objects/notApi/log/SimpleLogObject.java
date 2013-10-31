package project.lighthouse.autotests.objects.notApi.log;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;

public class SimpleLogObject {

    WebDriver driver;
    String message;

    private SimpleLogObject(WebDriver driver) {
        this.driver = driver;
    }

    public SimpleLogObject(WebDriver driver, WebElement element) {
        this(driver);
        setProperty(element);
    }

    private void setProperty(WebElement element) {
        this.message = element.findElement(By.xpath(".//*[@class='log__finalMessage']")).getText();
    }

    public String getMessage() {
        return this.message;
    }
}
