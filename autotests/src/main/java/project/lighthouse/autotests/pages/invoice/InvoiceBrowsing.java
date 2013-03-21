package project.lighthouse.autotests.pages.invoice;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import java.util.Map;

public class InvoiceBrowsing extends InvoiceCreatePage{

    @FindBy(xpath = "//*[@lh_link='edit']")
    private WebElement editButton;

    public InvoiceBrowsing(WebDriver driver) {
        super(driver);
    }

    public void CheckCardValue(String elementName, String expectedValue){
        WebElement element = getInvoiceWebElement(elementName);
        $(element).shouldContainText(expectedValue);
    }

    public void CheckCardValue(ExamplesTable checkValuesTable){
        for (Map<String, String> row : checkValuesTable.getRows()){
            String elementName = row.get("elementName");
            String expectedValue = row.get("expectedValue");
            CheckCardValue(elementName, expectedValue);
        }
    }

    public void EditButtonClick(){
        $(editButton).click();
    }


}
