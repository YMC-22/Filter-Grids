# 📖 Dependent Filter – Documentation

The **Dependent Filter** allows you to build a chain of interconnected dropdowns (taxonomy selectors), where the available terms in each level depend on the selections made in the previous one.  
Typical use case: `Brand → Model → Year`.

---

## ⚙️ Filter Settings Overview

Below are all the key options available for configuring the Dependent Filter.

---

### 1. Available Taxonomies
- Select the taxonomies that will participate in the filter.  
- Each selected taxonomy becomes a "level" in the dropdown chain.

---

### 2. Taxonomy Sequence
- Defines the **order** in which the selected taxonomies are displayed.  
- Example:  
  ```text
  brand, model, year
  ```
  → The filter will first show **Brand**, then **Model**, then **Year**.

---

### 3. Related Terms (Term Settings Modal)
- For each term, you can manually define related child terms in the next taxonomy.  
- Example:
  - Brand: *BMW* → Related Models: *X5, X6*  
  - Brand: *Audi* → Related Models: *A4, A6*  
- If no related terms are defined, the filter falls back to the **hierarchy** (`parent → child`).

---

### 4. Root Source
Defines how the **first level** terms are loaded:

- **Top-level terms** – automatically loads all top-level terms of the first taxonomy.  
- **Root terms manually** – you manually specify which terms should appear at the first level.

---

### 5. Mode (Single | Multiple)
Each taxonomy can work in one of two selection modes:

- **Single**
  - Only one term can be selected at a time.  
  - When a term is chosen, its name replaces the dropdown label (e.g., “Select Brand” → “BMW”).

- **Multiple**
  - Multiple terms can be selected.  
  - The dropdown label shows the count of selected items (e.g., “3 selected”).

---

### 6. Display all levels with placeholders
- **Enabled**  
  - All dropdowns in the sequence are visible from the start.  
  - Initially, only the first taxonomy has active terms.  
  - All other dropdowns are displayed in a disabled state with a placeholder (“No terms available”).  
  - As the user makes selections, the next dropdowns are populated and activated.  

- **Disabled** (Progressive Reveal)  
  - Dropdowns appear step by step.  
  - Only the first dropdown is visible initially.  
  - After choosing a term, the next dropdown is loaded and displayed.

---

### 7. Update Mode
Defines how the post grid is refreshed after changes in the filter:

- **Auto Update (default)**  
  - The grid updates automatically after every change.  
  - Recommended for most modern use cases.

- **Update on Apply Button**  
  - An **Apply** button is displayed below the dropdowns.  
  - The grid updates only when the user clicks **Apply**.  
  - Useful for heavy sites or when multiple selections are required.

---

### 8. Behavior on Clearing Selections
- If the user deselects terms at a higher level, all dependent dropdowns below are reset:
  - **Progressive mode** → dependent dropdowns are removed.  
  - **Placeholder mode** → dependent dropdowns remain but show “No terms available” and become disabled.  

- For the **first taxonomy**, if no terms are selected:
  - The filter falls back to its **default terms** (defined in `data-all-terms`).

---

## 🔗 Example Workflow

1. **User selects a Brand (BMW)**  
   → The Models dropdown loads with BMW models only.  

2. **User selects a Model (X5)**  
   → The Years dropdown loads with years related to BMW X5.  

3. **User deselects Brand**  
   → All dependent dropdowns (Model, Year) are reset:  
   - In **progressive mode**, they disappear.  
   - In **placeholder mode**, they stay visible but empty and disabled.  

---

## 🎨 Frontend Behavior

- Dropdowns use checkboxes for selection.  
- Each dropdown has a label:
  - **Single mode** → shows the selected term.  
  - **Multiple mode** → shows number of selected terms.  
- Disabled dropdowns have a greyed-out appearance (`.is-disabled`).  


## ✅ Best Practices

- Use **Auto Update** for small datasets (faster UX).  
- Use **Apply Button** for heavy datasets (avoid server overload).  
- Always define **Taxonomy Sequence** – the filter won’t work without it.  
- Use **Related Terms** if your taxonomies are not strictly hierarchical.  

---
