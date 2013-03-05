package project.lighthouse.autotests.steps;

import project.lighthouse.autotests.pages.ProductCardView;
import project.lighthouse.autotests.pages.ProductCreatePage;
import project.lighthouse.autotests.pages.ProductEditPage;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.ProductListPage;

public class ProductSteps extends ScenarioSteps{
	
	ProductCreatePage productCreatePage;
	ProductEditPage productEditPage;
	ProductCardView productCardView;
    ProductListPage productListPage;

	public ProductSteps(Pages pages) {
		super(pages);
	}
	
	@Step
	public void IsTheProductCreatePage(){
		productCreatePage.open();
	}
	
	@Step
	public void IsTheProductEditPage(){
		productEditPage.open();
	}
	
	@Step
	public void IsTheProductCardViewPage(){
		productCardView.open();
	}

    @Step
    public void IsTheProductListPageOpen(){
        productListPage.open();
    }
	
	@Step
	public void FieldInput(String elementName, String inputText){
		productCreatePage.input(elementName, inputText);
	}
	
	@Step
	public void SelectDropdown(String elementName, String value){
		productCreatePage.Select(elementName, value);
	}
	
	@Step
	public void CreateButtonClick(){
		productCreatePage.CreateButtonClick();
	}
	
	@Step
	public void FieldEdit(String elementName, String inputText){
		productEditPage.FieldEdit(elementName, inputText);
	}	
	
	@Step
	public void CancelButtonClick(){
		productEditPage.CancelButtonClick();
	}
	
	@Step
	public void CheckValue(String elementName, String expectedValue){
		productCardView.CheckValue(elementName, expectedValue);
	}

    @Step
    public void CreateNewProductButtonClick(){
        productListPage.CreateNewProductButtonClick();
    }

    @Step
    public void ListItemClick(String skuValue){
        productListPage.ListItemClick(skuValue);
    }

    @Step
    public void ListItemCheck(String skuValue){
        productListPage.ListItemChecks(skuValue);
    }

    @Step
    public void CheckProductWithSkuHasExpectedValue(String skuValue, String expectedValue){
        productListPage.CheckProductWithSkuHasExpectedValue(skuValue, expectedValue);
    }

    @Step
    public void CheckDropDownDefaultValue(String dropdownType, String expectedValue){
        productCreatePage.CheckDropDownDefaultValue(dropdownType, expectedValue);
    }
}
