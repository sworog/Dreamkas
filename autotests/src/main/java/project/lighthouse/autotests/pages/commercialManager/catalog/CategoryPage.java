package project.lighthouse.autotests.pages.commercialManager.catalog;


import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.InputOnlyVisible;

public class CategoryPage extends GroupPage {

    public CategoryPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addNewButtonClick() {
        new ButtonFacade(this, "Добавить категорию").click();
    }

    @Override
    public void createElements() {
        items.put("name", new InputOnlyVisible(this, "name"));
    }

    @Override
    public String getItemXpath(String name) {
        String groupXpath = "//*[@model='catalogCategory' and text()='%s']";
        return String.format(groupXpath, name);
    }
}
