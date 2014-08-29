package project.lighthouse.autotests.common;

public interface ModalWindowPageObject extends GeneralPageObject {

    public String getTitle();

    public String modalWindowXpath();

    public void confirmationOkClick();

    public void deleteButtonClick();

    public void confirmDeleteButtonClick();
}
