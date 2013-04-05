package project.lighthouse.autotests.pages.product;

import net.thucydides.core.annotations.DefaultUrl;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

@DefaultUrl("/product/edit")
public class ProductEditPage extends ProductCreatePage{
	
	@FindBy(xpath="//*[@lh_link='close']")
	private WebElement resetButton;

    public ProductEditPage(WebDriver driver) {
        super(driver);
    }

    public void cancelButtonClick(){
		$(resetButton).click();
	}
}
