# 0.1.0 - Initial Release

- A module for analyzing generic security vulnerabilities so that specific analysis can be built on top of it
- An implementation of the analysis module to check for susceptible usage of the extract function
- Support for printing a text result of analysis
- A SpraySole command provided to allow analysis of a single PHP file

# 0.2.0 - Directory support and Report printing

- SpraySole command adds support for analyzing a directory of PHP source files
- Support for printing an analysis report as a native PHP array, JSON structure or XML document
- Allow robust templating for all printers able to support a templating system

# 0.3.0 - GitHub support and SpraySole configuration

- SpraySole command added that allows the cloning and analysis of a public repository from GitHub
- Allowing the configuration of SpraySole to determine the type of printer and other various options
- Allow support for outputting multiple report formats

# 0.4.0 - Inspection Module and Variable extract support

- Add a module to abstract the inspection and details of that inspection for a targeted analysis
- Add support for analyzing dynamic use of the extract function (e.g., `call_user_func('extract', $arr)`)
