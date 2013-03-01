package project.lighthouse.autotests.steps;

import project.lighthouse.autotests.pages.ProductCardView;
import project.lighthouse.autotests.pages.ProductCreatePage;
import project.lighthouse.autotests.pages.ProductEditPage;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;

public class ProductSteps extends ScenarioSteps{
	
	ProductCreatePage productCreatePage;
	ProductEditPage productEditPage;
	ProductCardView productCardView;

	public ProductSteps(Pages pages) {
		super(pages);
	}
	
	@Step
	public void IstheOrderCreatePage(){
		productCreatePage.open();
	}
	
	@Step
	public void IsTheOrderEditPage(){
		productEditPage.open();
	}
	
	@Step
	public void IsTheOrderCardViewPage(){
		productCardView.open();
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
	public void EditButtonClick(){
		productEditPage.EditbuttonClick();
	}
	
	@Step
	public void CancelButtonClick(){
		productEditPage.CancelButtonClick();
	}
	
	@Step
	public void CheckValue(String elementName, String expectedValue){
		productCardView.CheckValue(elementName, expectedValue);
	}
}
