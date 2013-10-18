package project.lighthouse.autotests.objects;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;

import java.util.Map;

import static junit.framework.Assert.assertEquals;

public class ProductInvoiceListObject {

    private WebDriver driver;
    private String date;
    private String number;
    private String price;
    private WebElement element;

    public String getDate() {
        return date;
    }

    public String getNumber() {
        return number;
    }

    public String getPrice() {
        return price;
    }

    private ProductInvoiceListObject(WebDriver driver) {
        this.driver = driver;
    }

    public ProductInvoiceListObject(WebDriver driver, WebElement element) {
        this(driver);
        this.element = element;
        setProperties(element);
    }

    private void setProperties(WebElement element) {
        date = element.findElement(By.xpath("")).getText();
        number = element.findElement(By.xpath("")).getText();
        price = element.findElement(By.xpath("")).getText();
    }

    public void click() {
        element.click();
    }

    public void compareWithExamplesTableRow(Map<String, String> row) {
        assertEquals(date, row.get("date"));
        assertEquals(number, row.get("number"));
        assertEquals(price, row.get("price"));
    }

    public Boolean rowIsEqual(Map<String, String> row) {
        return date.equals(row.get("date")) &&
                number.equals(row.get("number")) &&
                price.equals(row.get("price"));
    }
}
