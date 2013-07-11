package project.lighthouse.autotests.pages.commercialManager.catalog;

import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.elements.Input;

public class SubCategoryPage extends GroupPage {

    @FindBy(xpath = "//*[@class='button button_color_blue catalog__addSubcategoryLink editor__control']")
    WebElementFacade addNewSubCategoryButton;

    public SubCategoryPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("name", new Input(this, By.xpath("")));
    }

    @Override
    public void addNewButtonClick() {
        addNewSubCategoryButton.click();
    }

//    @Override
//    public void addNewButtonConfirmClick() {
//        findBy("").click();
//        preloaderWait();
//    }

    @Override
    public String getItemXpath(String name) {
        String groupXpath = "//*[@model_name='catalogSubcategory' and text()='%s']";
        return String.format(groupXpath, name);
    }

    @Override
    public CommonItem getItem() {
        return items.get("name");
    }
}
