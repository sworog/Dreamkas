package project.lighthouse.autotests.pages.invoice;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.CommonPageInterface;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.pages.common.CommonPage;
import project.lighthouse.autotests.pages.common.CommonView;
import java.util.Map;

public class InvoiceBrowsing extends InvoiceCreatePage{

    @FindBy(xpath = "//*[@lh_link='edit']")
    private WebElement editButton;

    @FindBy(name = "product")
    private WebElement invoiceProductListItem;

    @FindBy(xpath = "//*[@class='saveInvoiceAndAddProduct']")
    private WebElement goToTheaAdditionOfProductsLink;

    @FindBy(xpath = "//*[@class='addMoreProduct']")
    private WebElement addOneMoreProductLink;

    private static final String XPATH = "//../*[span[@name='productSku' and normalize-space(text())='%s']]";
    CommonViewInterface commonViewInterface = new CommonView(getDriver(), XPATH, invoiceProductListItem);
    CommonPageInterface commonPageInterface = new CommonPage(getDriver());

    public InvoiceBrowsing(WebDriver driver) {
        super(driver);
    }

    public void checkCardValue(String elementName, String expectedValue){
        WebElement element = items.get(elementName).getWebElement();
        commonPageInterface.shouldContainsText(elementName, element, expectedValue);
    }

    public void shouldContainsText(String elementName, String expectedValue){
        WebElement element = items.get(elementName).getWebElement();
        commonPageInterface.shouldContainsText(elementName, element, expectedValue);
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

    public void goToTheaAdditionOfProductsLinkClick(){
        $(goToTheaAdditionOfProductsLink).click();
    }

    public void addOneMoreProductLinkClick(){
        $(addOneMoreProductLink).click();
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
