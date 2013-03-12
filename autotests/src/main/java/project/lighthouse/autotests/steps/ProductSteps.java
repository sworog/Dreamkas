package project.lighthouse.autotests.steps;

import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.*;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;

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
    public void IsTheProductCardOpen(){
        productCardView.open();
    }
	
	@Step
	public void FieldInput(String elementName, String inputText){
		productCreatePage.FieldType(elementName, inputText);
	}
	
	@Step
	public void SelectDropDown(String elementName, String value){
		productCreatePage.SelectByValue(elementName, value);
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
	public void CheckCardValue(String elementName, String expectedValue){
		productCardView.CheckCardValue(elementName, expectedValue);
	}

    @Step
    public void CheckCardValue(ExamplesTable checkValuesTable){
        productCardView.CheckCardValue(checkValuesTable);
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
        productListPage.ListItemCheck(skuValue);
    }

    @Step
    public void CheckProductWithSkuHasExpectedValue(String skuValue, String expectedValue){
        productListPage.CheckProductWithSkuHasExpectedValue(skuValue, expectedValue);
    }

    @Step
    public void CheckDropDownDefaultValue(String dropDownType, String expectedValue){
        productCreatePage.CheckDropDownDefaultValue(dropDownType, expectedValue);
    }

    @Step
    public void EditButtonClick(){
        productCardView.EditButtonClick();
    }

    @Step
    public void FieldType(ExamplesTable fieldInputTable){
        productCreatePage.FieldType(fieldInputTable);
    }

    @Step
    public void FieldEdit(ExamplesTable fieldInputTable){
        productEditPage.FieldEdit(fieldInputTable);
    }
}
