package project.lighthouse.autotests.pages;

import java.util.List;
import java.util.Map;
import java.util.NoSuchElementException;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import net.thucydides.core.annotations.DefaultUrl;

@DefaultUrl("/index.xml?product")
public class ProductCardView extends ProductCreatePage{

    @FindBy(xpath = "//span[@title='Название']")
	private WebElement nameValue;

    @FindBy(xpath = "//span[@title='Артикул']")
    private WebElement skuValue;
	
	@FindBy(xpath="//div[@lh_prop_set]")
	private List<WebElement> lh_block_Values;

    @FindBy(xpath = "//a[@lh_button='modify']")
    private WebElement editButton;
	
	public ProductCardView(WebDriver driver) {
		super(driver);
	}
	
	public void CheckCardValue(String elementName, String expectedValue){
        switch (elementName){
            case "sku":
            case "name":
                WebElement element = getWebElement(elementName);
                $(element).shouldContainText(expectedValue);
                break;
            default:
                boolean IsContains = false;
                for (WebElement webElement : lh_block_Values) {
                    if ($(webElement).containsText(expectedValue)){
                        IsContains = true;
                    }
                }
                if (!IsContains){
                    String errorMessage = String.format("The value '%s' is not '%s'", elementName, expectedValue);
                    throw new AssertionError(errorMessage);
                }
                break;
        }
	}

    public void CheckCardValue(ExamplesTable checkValuesTable){
        for (Map<String, String> row : checkValuesTable.getRows()){
            String elementName = row.get("elementName");
            String expectedValue = row.get("expectedValue");
            CheckCardValue(elementName, expectedValue);
        }
    }
	
	public WebElement getWebElement(String elementName){
		switch (elementName) {
		case "sku":
			return skuValue;
		case "name":
			return nameValue;
		default:
            return (WebElement)new AssertionError("No such value!");
		}		
	}

    public void EditButtonClick(){
        $(editButton).click();
    }
}
