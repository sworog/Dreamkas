package project.lighthouse.autotests.pages.invoice;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.ICommonViewInterface;
import project.lighthouse.autotests.pages.common.ICommonView;

import java.util.Map;

public class InvoiceBrowsing extends InvoiceCreatePage{

    @FindBy(xpath = "//*[@lh_link='edit']")
    private WebElement editButton;

    public InvoiceBrowsing(WebDriver driver) {
        super(driver);
    }

    public void CheckCardValue(String elementName, String expectedValue){
        WebElement element = items.get(elementName).GetWebElement();
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
    ICommonViewInterface iCommonViewInterface = new ICommonView(getDriver(), XPATH, invoiceProductListItem);

    public void goToTheaAdditionOfProductsLinkClick(){
        $(goToTheaAdditionOfProductsLink).click();
    }

    public void addOneMoreProductLinkClick(){
        $(addOneMoreProductLink).click();
    }

    public void invoiceFinishClick(){
        $(invoiceFinish).click();
    }

    public void TotalCalculation(){
        /*
        ???????????? Count all sum by itself?
         */
    }

    public void ListItemClick(String value){
        iCommonViewInterface.ItemClick(value);

    }

    public void ListItemCheck(String value){
        iCommonViewInterface.ItemCheck(value);
    }

    public void CheckListItemWithSkuHasExpectedValue(String value, String elementName, String expectedValue){
        iCommonViewInterface.CheckInvoiceListItemWithSkuHasExpectedValue(value, elementName, expectedValue);



    }
}
