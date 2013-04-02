package project.lighthouse.autotests.pages.invoice;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.pages.common.CommonView;

import java.util.Map;

public class InvoiceBrowsing extends InvoiceCreatePage{

    @FindBy(xpath = "//*[@lh_link='edit']")
    private WebElement editButton;

    public InvoiceBrowsing(WebDriver driver) {
        super(driver);
    }

    public void checkCardValue(String elementName, String expectedValue){
        WebElement element = items.get(elementName).getWebElement();
        $(element).shouldContainText(expectedValue);
    }

    public void shouldContainsText(String elementName, String expectedValue){
        WebElement element = items.get(elementName).getWebElement();
        CommonPageInterface.shouldContainsText(element, expectedValue);
    }

    public void checkCardValue(ExamplesTable checkValuesTable){
        for (Map<String, String> row : checkValuesTable.getRows()){
            String elementName = row.get("elementName");
            String expectedValue = row.get("expectedValue");
            checkCardValue(elementName, expectedValue);
        }
    }

    public void editButtonClick(){
        $(editButton).click();
    }
     /*
    8.3 story code
     */

    @FindBy(name = "product")
    private WebElement invoiceProductListItem;

    @FindBy(name = "link")
    private WebElement goToTheaAdditionOfProductsLink;

    @FindBy(name = "addOneMoreProductLink")
    private WebElement addOneMoreProductLink;

    @FindBy(name = "invoiceFinish")
    private WebElement invoiceFinish;

    @FindBy(name = "total")
    private WebElement totalInfo;

    private static final String XPATH = "";
    CommonViewInterface commonViewInterface = new CommonView(getDriver(), XPATH, invoiceProductListItem);

    public void goToTheaAdditionOfProductsLinkClick(){
        $(goToTheaAdditionOfProductsLink).click();
    }

    public void addOneMoreProductLinkClick(){
        $(addOneMoreProductLink).click();
    }

    public void invoiceFinishClick(){
        $(invoiceFinish).click();
    }

    public void totalCalculation(){
        /*
        ???????????? Count all sum by itself?
         */
    }

    public void listItemClick(String value){
        commonViewInterface.itemClick(value);

    }

    public void listItemCheck(String value){
        commonViewInterface.itemCheck(value);
    }

    public void checkListItemWithSkuHasExpectedValue(String value, String elementName, String expectedValue){
        commonViewInterface.checkInvoiceListItemWithSkuHasExpectedValue(value, elementName, expectedValue);
    }
}
