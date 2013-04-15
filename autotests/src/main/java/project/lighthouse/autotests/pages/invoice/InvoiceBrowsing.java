package project.lighthouse.autotests.pages.invoice;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.pages.common.CommonView;
import java.util.Map;

public class InvoiceBrowsing extends InvoiceCreatePage{

    private static final String ITEM_NAME = "product";
    private static final String ITEM_SKU_NAME = "productSku";
    CommonViewInterface commonViewInterface = new CommonView(getDriver(), ITEM_NAME, ITEM_SKU_NAME);

    @FindBy(xpath = "//*[@lh_link='edit']")
    private WebElement editButton;

    @FindBy(xpath = "//*[@class='saveInvoiceAndAddProduct']")
    private WebElement goToTheaAdditionOfProductsLink;

    @FindBy(xpath = "//*[@class='addMoreProduct']")
    private WebElement addOneMoreProductLink;

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
        commonViewInterface.checkListItemWithSkuHasExpectedValue(value, elementName, expectedValue);
    }
}
